<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class RAM_REST_Live_Controller  extends WP_REST_Controller{ 
  
   

    public function __construct() {
        
        $this->namespace     = 'watch-life-net/v1';
        $this->resource_name = 'live';
    }

    // Register our routes.
    public function register_routes() {
        register_rest_route( $this->namespace, '/' . $this->resource_name.'/refreshliveinfo', array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'refreshliveinfo' ),
                'args'               => array( 
                    'openid' => array(
                        'required' => true
                    ),
                    // 'userid' => array(
                    //     'required' => true
                    // )
                ),
                'permission_callback' => array( $this, 'refresh_life_info_permissions_check' )
                 
            ),
            // Register our schema callback.
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );

        register_rest_route( $this->namespace, '/' . $this->resource_name.'/getliveinfo', array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'getliveinfo' )
                // 'permission_callback' => array( $this, 'get_pages_about_permissions_check' )
                 
            ),
            // Register our schema callback.
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );    
       
    } 
    
    public  function  getliveinfo($request){
        $liveInfo_option  =get_option('wf-liveInfo-option');
         $response = rest_ensure_response($liveInfo_option);
         return $response;

    }

    public  function  refreshliveinfo($request){
        $data = array(
            'start' =>0,
            'limit' =>100

        );
        $updateResult=true;        
        $liveInfo = RAM()->wxapi->getliveinfo($data);       
        $errcode=$liveInfo['errcode'];
        $errmsg=$liveInfo['errmsg'];     
         if($errcode == 0 ) {
             if(!empty(get_option('wf-liveInfo-option')))
             {
                delete_option('wf-liveInfo-option');
             }
            $updateResult= update_option( 'wf-liveInfo-option', $liveInfo);
            $message=$updateResult?"更新成功":"更新失败";        
        }
        else{

            if ($errcode==1 || $errcode=9410000)
            {
                if(!empty(get_option('wf-liveInfo-option')))
                {
                    delete_option('wf-liveInfo-option');
                }

                $message="未创建直播间或直播间列表为空";

            }
            else
            {
                $message=$errmsg;
            }
            
           
        }
      
        $result = array('success' => $errcode ,'message'=>$message,'liveInfo'=>$liveInfo);
        $response = rest_ensure_response( $result );
         return $response;
        
    }

    /**
     * Check whether a given request has permission to read products.
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return WP_Error|boolean
     */
    public function refresh_life_info_permissions_check( $request ) {
        $openId =$request['openid'];
        $user = get_user_by( 'login', $openId);
        if(empty($user))
        {
            return new WP_Error( 'error', '此用户不存在' , array( 'status' => 500 ) );
        }  
        $userLevel= getUserLevel($user->ID);
        if( $userLevel['level'] !='10')
        {
            return new WP_Error( 'error', '没有权限' , array( 'status' => 500 ) );

        }
        

		return true;
    }

    /**
     * Check whether a given request has permission to read products.
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return WP_Error|boolean
     */
    public function get_live_qrcode_permissions_check( $request ) {
        $roomid =isset($request['id'])?(int)$request['id']:0;
       if($roomid == 0)
       {

           return new WP_Error( 'error', '参数错误', array( 'status' => 400 ) );

       }        
       return true;
   }
}
