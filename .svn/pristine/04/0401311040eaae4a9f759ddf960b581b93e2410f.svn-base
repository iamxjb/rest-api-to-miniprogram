<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


class RAM_REST_Categories_Controller  extends WP_REST_Controller{

    public function __construct() {
        
        $this->namespace     = 'watch-life-net/v1';
        $this->resource_name = 'category';
    }

     public function register_routes() {
        register_rest_route( $this->namespace, '/' . $this->resource_name.'/getsubscription', array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'getSubscription' ),
                'permission_callback' => array( $this, 'get_item_permissions_check' ),
                'args'               => array(              
                    'openid' => array(
                        'required' => true
                    )
                )
                 
            ),
            // Register our schema callback.
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );

        register_rest_route( $this->namespace, '/' . $this->resource_name.'/postsubscription', array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'POST',
                'callback'  => array( $this, 'postSubscription' ),
                'permission_callback' => array( $this, 'post_item_permissions_check' ),
                'args'               => array(              
                    'openid' => array(
                        'required' => true
                    ),
                    'categoryid' => array(
                        'required' => true
                    )
                )
                 
            ),
            // Register our schema callback.
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );

        register_rest_route( $this->namespace, '/' . $this->resource_name.'/ids', array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'get_categories_ids' ),
                //'permission_callback' => array( $this, 'get_item_permissions_check' )
                 
            ),
            // Register our schema callback.
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );

    }

    

    public function postSubscription($request)
    {
        global $wpdb;
        $openid= $request['openid'];
        $categoryid=$request['categoryid'];
        $user_id =0;
        $user = get_user_by( 'login', $openid);
        if($user)
        {
            $user_id = $user->ID;
            if(!empty($user_id))
            {
                $sql =$wpdb->prepare("SELECT *  FROM ".$wpdb->usermeta ." WHERE user_id=%d and meta_key='wl_sub' and meta_value=%s",$user_id,$categoryid);
                $usermetas = $wpdb->get_results($sql);
                $count =count($usermetas);
                if ($count==0)
                {
                    
                    if(add_user_meta($user_id, "wl_sub",$categoryid,false))
                    {
                        $result["code"]="success";
                        $result["message"]= "订阅成功";
                        $result["status"]="200";    
                        
                    
                    }
                    else
                    {
                        $result["code"]="success";
                        $result["message"]= "订阅失败";
                        $result["status"]="500";                   
                        
                    }
                    
                }
                else
                {
                        if (delete_user_meta($user_id,'wl_sub',$categoryid))
                        {
                            
                                $result["code"]="success";
                                $result["message"]= "取消订阅成功";
                                $result["status"]="201";
                            
                            
                        }
                        else
                        {
                                $result["code"]="success";
                                $result["message"]= "取消订阅失败";
                                $result["status"]="501";                   
                                
                            
                        }

                }
            }
            else
            {
                $result["code"]="success";
                $result["message"]= "用户参数错误";
                $result["status"]="500";

            }

        }
        else
        {

            $result["code"]="success";
            $result["message"]= "用户参数错误";
            $result["status"]="500";

        }

        $response = rest_ensure_response($result);
        return $response;

    }

    public function getSubscription($request)
    {
        global $wpdb;
        $openid= $request['openid'];
        $user_id =0;
        $user = get_user_by( 'login', $openid);
        if($user)
        {
            $user_id = $user->ID;
            $usermeta = get_user_meta($user_id);
            if (!empty($usermeta))
            {
                    //$usermetaList =$wpdb->get_results($sql);        
                    $result["code"]="success";
                    $result["message"]= "获取订阅成功";
                    $result["status"]="200";                    
                    if(!empty($usermeta['wl_sub']))
                    {
                        $result["subscription"]=$usermeta['wl_sub'];
                        $substr=implode(",",$usermeta['wl_sub']);
                        $result["substr"]=$substr; 
                        $sql="SELECT SQL_CALC_FOUND_ROWS  ".$wpdb->posts.".ID ,".$wpdb->posts.".post_title  FROM ".$wpdb->posts."  LEFT JOIN ".$wpdb->term_relationships." ON (".$wpdb->posts.".ID = ".$wpdb->term_relationships.".object_id) WHERE 1=1  AND ( ".$wpdb->term_relationships.".term_taxonomy_id IN (".$substr.")) AND ".$wpdb->posts.".post_type = 'post' AND (".$wpdb->posts.".post_status = 'publish') GROUP BY ".$wpdb->posts.".ID ORDER BY ".$wpdb->posts.".post_date DESC LIMIT 0, 20";
                        $usermetaList =$wpdb->get_results($sql); 
                        $result["usermetaList"]=$usermetaList;

                    } 
            }
            else
            {
                $result["code"]="success";
                $result["message"]= "没有订阅的专栏";
                $result["status"]="501";                   
                    
                
            }
            
        }
        else
        {
            $result["code"]="success";
            $result["message"]= "用户参数错误";
            $result["status"]="501";                   
                
            
        }

        $response = rest_ensure_response($result);
        return $response; 



    }

    public  function  get_categories_ids()
    {
        $categoriesId =get_option('wf_display_categories');
       

        $result['Ids'] =$categoriesId;
        
        $response = rest_ensure_response($result); 
        return $response;
    }

    public function post_item_permissions_check($request)
    {
        $openid= $request['openid'];
        $categoryid=$request['categoryid'];
        if(empty($openid) || empty($categoryid) )
        {
            return new WP_Error( 'error', '参数错误', array( 'status' => 500 ) );
        }
        else{
            if(!username_exists($openid))
            {
                return new WP_Error( 'error', '不允许提交', array( 'status' => 500 ) );
            }

        }

        return true;
    }

    public function get_item_permissions_check($request ) {
        $openid= $request['openid'];
        if(empty($openid) )
        {
            return new WP_Error( 'error', '参数错误', array( 'status' => 500 ) );
        }    
    
        else
        { 
            if(!username_exists($openid))
            {
                return new WP_Error( 'error', '不允许提交', array( 'status' => 500 ) );
            }
        
        }
        return true;
    }

}

