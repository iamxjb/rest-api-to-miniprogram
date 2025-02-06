<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class RAM_REST_Weixin_Controller  extends WP_REST_Controller{

    public function __construct() {
        $this->namespace     = 'watch-life-net/v1';
        $this->resource_name = 'weixin';
    }

    public function register_routes() {
        register_rest_route( $this->namespace, '/' . $this->resource_name.'/qrcodeimg', array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'POST',
                'callback'  => array( $this, 'getWinxinQrcodeImg' ),
                'permission_callback' => array( $this, 'get_qrcodeimg_permissions_check' ),
                'args'               => array(              
                    'postid' => array(
                        'required' => true
                    )
                )
                 
            ),                        
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );


        register_rest_route( $this->namespace, '/' . $this->resource_name.'/sendmessage', array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'POST',
                'callback'  => array( $this, 'sendmessage' ),
                'permission_callback' => array( $this, 'send_message_permissions_check' ),
                'args'               => array(              
                    'openid' => array(
                        'required' => true
                    ),                    
                    'template_id' => array(
                        'required' => true
                    ),
                    'postid' => array(
                        'required' => true
                    ),
                    'form_id' => array(
                        'required' => true
                    ),
                    'total_fee' => array(
                        'required' => true
                    ),
                    'flag' => array(
                        'required' => true
                    ),
                    'fromUser' => array(
                        'required' => true
                    )
                    
                )
                 
            ),            
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );

        register_rest_route( $this->namespace, '/' . $this->resource_name.'/getopenid', array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'POST',
                'callback'  => array( $this, 'getOpenid' ),
                'permission_callback' => array( $this, 'get_openid_permissions_check' ),
                'args'               => array(              
                    'js_code' => array(
                        'required' => true
                    ),                    
                    'encryptedData' => array(
                        'required' => true
                    ),
                    'iv' => array(
                        'required' => true
                    ),
                    'avatarUrl' => array(
                        'required' => true
                    ),
                    'nickname' => array(
                        'required' => true
                    )
                )
                 
            ),            
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );

        register_rest_route( $this->namespace, '/' . $this->resource_name.'/getuserinfo', array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'getUserInfo' ),
                'permission_callback' => array( $this, 'get_userInfo_permissions_check' ),
                'args'               => array(              
                    'openid' => array(
                        'required' => true
                    )
                )
                 
            ),            
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );

        register_rest_route( $this->namespace, '/' . $this->resource_name . '/userlogin', array(
            array(
                'methods'             => 'POST',
                'callback'            => array( $this, 'userlogin' ),
                'permission_callback' => array( $this, 'get_openid_permissions_check' ),
                'args'                => array(
                    'context' => $this->get_context_param( array( 'default' => 'view' ) ),                    
                    'avatarUrl' => array(
                        'required' => true
                    ),
                    'nickname' => array(
                        'required' => true
                    ),
                    'js_code' => array(
                        'required' => true
                    )
                )
            ),
            'schema' => array( $this, 'get_public_item_schema' ),
            ) );

            register_rest_route( $this->namespace, '/' . $this->resource_name . '/webchatuserlogin', array(
                array(
                    'methods'             => 'POST',
                    'callback'            => array( $this, 'userlogin' ),
                    'permission_callback' => array( $this, 'get_openid_permissions_check' ),
                    'args'                => array(
                        'context' => $this->get_context_param( array( 'default' => 'view' ) ),                  
                        'js_code' => array(
                            'required' => true
                        )
                    )
                ),
                'schema' => array( $this, 'get_public_item_schema' ),
                ) );

                register_rest_route($this->namespace, '/' . $this->resource_name . '/updatenickname', array(
                    array(
                        'methods'             => 'POST',
                        'callback'            => array($this, 'updateNickname'),
                        'permission_callback' => array($this, 'get_openid_permissions_check'),
                        'args'                => array(
                            'context' => $this->get_context_param(array('default' => 'view')),
                            'js_code' => array(
                                'required' => true
                            ),
                            'nickname' => array(
                                'required' => true
                            )
                        )
                    ),
                    'schema' => array($this, 'get_public_item_schema'),
                ));

                register_rest_route($this->namespace, '/' . $this->resource_name . '/updatewechatshopinfo', array(
                    array(
                        'methods'             => "POST",
                        'callback'            => array($this, 'update_user_wechatshop_info'),
                        'permission_callback' => array($this, 'update_user_wechatshop_info_permissions_check'),
                        'args'                => array(
                            'openid' => array(
                                'required' => true
                            ),                            
                            'storeappid' => array(
                                'required' => true
                            ),

				),
        
                    ),
                    'schema' => array($this, 'get_public_item_schema'),
                ));
                // register_rest_route($this->namespace, '/' . $this->resource_name . '/getcallbackip', array(
                //     array(
                //         'methods'             => 'GET',
                //         'callback'            => array($this, 'get_callbackip'),
                //         'permission_callback' => array($this, 'get_userInfo_permissions_check'),
                //         'args'                => array(
                //             'context' => $this->get_context_param(array('default' => 'view')),
                            
                //         )
                //     ),
                //     'schema' => array($this, 'get_public_item_schema'),
                // ));
               

    }

    // function get_callbackip() {
    //     $data=array();
    //     $ip= RAM()->wxapi->get_callbackip($data); 
    //     $response = rest_ensure_response($ip);
    //     return $response;   
    // }

    function get_shop_list($request) {
        $data=new class
        {
        };
        $result =  RAM()->wxapi->get_cooperation_shop_list($data);
        return $result;
    } 

    public function  update_user_wechatshop_info($request)
	{
		$userId = (int)$request['userid'];
		$storeAppId = $request['storeappid'];

		$storeLocation = $request['storelocation'];
		$storeAddress = $request['storeaddress'];
		$storeLatitude = $request['storelatitude'];
		$storeLongitude = $request['storelongitude'];

		$storeName="";
		$flag=false;
		$data=new class
        {
        };
        $result =  RAM()->wxapi->get_cooperation_shop_list($data);
		if($result['errcode']==0)
		{
			$shop_list=$result['shop_list'];
			foreach($shop_list as $item)
			{
				if($item['status']==1 &&  $item['appid']==$storeAppId)
				{
					$storeName=$item['nickname'];
					$flag=true;
					break;
				}
			}
		}
		else
		{
			return new WP_Error('error', '你的微信小店尚未绑定本小程序', array('status' => 200));
		}

		if($flag)
		{
			$storeInfo=array(
				'storeappid'=>$storeAppId,
				'storename'=>$storeName,
				'storelocation'=>$storeLocation,
				'storeaddress'=>$storeAddress,
				'storelatitude'=>$storeLatitude,
				'storelongitude'=>$storeLongitude
			);
			if(!empty($storeInfo) && !empty($storeLocation))
			{
				update_user_meta($userId, 'storeinfo', $storeInfo);
			}
			else
			{
				update_user_meta($userId, 'storeinfo', '');
			}

			
			update_user_meta($userId, 'storeappid', $storeAppId);
			update_user_meta($userId, 'storename', $storeName);			
			$response = array('success' => true, 'message' => '设置成功','storeappid'=>$storeAppId,'storename'=>$storeName,'storeinfo'=>$storeInfo);	
			$response = rest_ensure_response($response);
			return $response;
		}
		else
		{
			return new WP_Error('error', '你的微信小店尚未绑定本小程序', array('status' => 200));
		}

		
	}
    
    function updateNickname($request)
    {
        $js_code= $request['js_code']; 
        $api_result=jscode2session($js_code);
        if($api_result['errcode'] !=0)
        {
            return new WP_Error( 'error', $api_result['errmsg'], array( 'status' => 500 ) );
        }          
        $openId = $api_result['openid'];            
        $user = get_user_by('login', $openId);
        if(empty($user)) {   
            return new WP_Error('error', '用户参数错误', array( 'status' => 502 ) );
        }
        $userId=(int)$user->ID;
        $nickname=$request['nickname'];  
        $count = ram_strLength($nickname);
        if($count>15)
        {
            return new WP_Error( 'error', '用户昵称不能超过15个字符', array( 'status' => 200 ) );
        }


        $data = array(
            'content' =>$nickname,
            'openid'   =>$openId,
            'nickname'=>$nickname,
            'scene'   =>2,
            'version'=>2
            
        );

        $msgSecCheckResult=security_msgSecCheck($data);
        $errcode=$msgSecCheckResult['errcode'];		
        $errmsg=$msgSecCheckResult['errmsg'];
        if($errcode !=0)
        {
            return new WP_Error( 'error', $errmsg, array( 'status' => 200 ) );
        }
        $nickname=filterEmoji($nickname);
        $_nickname=base64_encode($nickname);          
        $_nickname=strlen($_nickname)>49?substr($_nickname,49):$_nickname; 
        $userdata =array(
            'ID'            => $userId,
            'first_name'	=> $nickname,
            'nickname'      => $nickname,
            'user_nicename' => $_nickname,
            'display_name'  => $nickname
        );
        $userId =wp_update_user($userdata);
        if(is_wp_error($userId)){
            return new WP_Error( 'error', '设置错误' , array( 'status' => 500 ) );
        } 
   
        $result["code"]="success";            
        $result["message"]= "设置成功";
        $result["status"]="200";
        $result["nickname"]=$nickname;                   
        $response = rest_ensure_response($result);
        return $response; 
        
    }
    
    function getUserInfo($request)
    {
      
        $openId =$request['openid'];
        $_user = get_user_by( 'login', $openId);  
        if(empty($_user ))
        {
            return new WP_Error( 'error', '无此用户信息', array( 'status' => 500 ) );
       
        }
        else{

            $user['nickname']=$_user->display_name;
            $avatar= get_user_meta($_user->ID, 'avatar', true );
            if(empty($avatar))
            {
                $avatar = plugins_url()."/".REST_API_TO_MINIPROGRAM_PLUGIN_NAME."/includes/images/gravatar.png";
            }

            $userLevel=getUserLevel($_user->ID);
            $user['userLevel']=$userLevel;            
            $user['avatar']=$avatar;            
            $result["code"]="success";
            $result["message"]= "获取用户信息成功";
            $result["status"]="200";
            $result["user"]=$user;
            $response = rest_ensure_response($result);
            return $response;

        }
    }
    function getOpenid($request)
    {
        $js_code= $request['js_code'];
        $encryptedData=$request['encryptedData'];
        $iv=$request['iv'];
        $avatarUrl=$request['avatarUrl'];
        $nickname=empty($request['nickname'])?'':$request['nickname'];
        $appid = get_option('wf_appid');
        $appsecret = get_option('wf_secret');
        if(empty($appid) || empty($appsecret) ){
            return new WP_Error( 'error', 'appid或appsecret为空', array( 'status' => 500 ) );
        }
        else
        {        
            $access_url = "https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$appsecret."&js_code=".$js_code."&grant_type=authorization_code";
            $access_result = https_request($access_url);
            if($access_result=='ERROR') {
                return new WP_Error( 'error', 'API错误：' . json_encode($access_result), array( 'status' => 501 ) );
            } 
            $api_result  = json_decode($access_result,true);            
            if( empty( $api_result['openid'] ) || empty( $api_result['session_key'] )) {
                return new WP_Error('error', 'API错误：' . json_encode( $api_result ), array( 'status' => 502 ) );
            }            
            $openId = $api_result['openid']; 
            $sessionKey = $api_result['session_key'];  
            $unionId = $api_result['unionid'];                      
            // $access_result =decrypt_data($appid, $sessionKey,$encryptedData, $iv, $data);                   
            // if($access_result !=0) {
            //     return new WP_Error( 'error', '解密错误：' . $access_result, array( 'status' => 503 ) );
            // }
            $userId=0;           
            // $data = json_decode( $data, true );  
            $nickname=filterEmoji($nickname);         
            $_nickname=base64_encode($nickname);          
		    $_nickname=strlen($_nickname)>49?substr($_nickname,49):$_nickname;
            // $avatarUrl= $data['avatarUrl'];             
            if(!username_exists($openId) ) {                
                $new_user_data = apply_filters( 'new_user_data', array(
                    'user_login'    => $openId,
                    'first_name'	=> $nickname ,
                    'nickname'      => $nickname,                    
                    'user_nicename' => $_nickname,
                    'display_name'  => $nickname,
                    'user_pass'     => $openId,
                    'user_email'    => $openId.'@weixin.com'
                ) );                
                $userId = wp_insert_user( $new_user_data );			
                if ( is_wp_error( $userId ) || empty($userId) ||  $userId==0 ) {
                    return new WP_Error( 'error', '插入wordpress用户错误：', array( 'status' => 500 ) );				
                }

                update_user_meta( $userId,'avatar',$avatarUrl);
                update_user_meta($userId,'usertype',"weixin");
                update_user_meta($userId,'unionId',$unionId);

            }            
            else{
                $user = get_user_by( 'login', $openId);     
                $userdata =array(
                    'ID'            => $user->ID,
                    'first_name'	=> $nickname,
                    'nickname'      => $nickname,
                    'user_nicename' => $_nickname,
                    'display_name'  => $nickname,
                    'user_email'    => $openId.'@weixin.com'
                );
                $userId =wp_update_user($userdata);
                if(is_wp_error($userId)){
                    return new WP_Error( 'error', '更新wp用户错误：' , array( 'status' => 500 ) );
                }             
                update_user_meta($userId,'avatar',$avatarUrl);
                update_user_meta($userId,'usertype',"weixin","weixin");
                update_user_meta($userId,'unionId',$unionId);
                  
            }
            $userLevel= getUserLevel($userId);
            $result["code"]="success";
            
            $result["message"]= "获取用户信息成功";
            $result["status"]="200";
            $result["openid"]=$openId;
            $result["userLevel"]=$userLevel;            
            $response = rest_ensure_response($result);
            return $response; 
        }  
    }

    function userlogin($request)
    {
        $js_code= $request['js_code'];       
        $wxAvatarUrl=$request['avatarUrl'];
        $wxNickname=$request['nickname']; 
        $api_result=jscode2session($js_code);
        if($api_result['errcode'] !=0)
        {
            return new WP_Error( 'error', $api_result['errmsg'], array( 'status' => 500 ) );
        }           
        $openId = $api_result['openid'];
        $unionId = $api_result['unionid']; 
        $userId=0;
        $_nickname='';
        $nickname='微信用户';
        $avatarUrl = plugins_url() . '/' . REST_API_TO_MINIPROGRAM_PLUGIN_NAME . '/includes/images/gravatar.png';
        if(!empty($wxNickname) && $wxNickname !='微信用户')
        {
            $avatarUrl =$wxAvatarUrl;
            $nickname=filterEmoji($wxNickname);         
            $_nickname=base64_encode($nickname);          
            $_nickname=strlen($_nickname)>49?substr($_nickname,49):$_nickname;
        }
        else
        {
            $nickname='微信用户'.ram_randString();
            $_nickname = $nickname;
        }
        $display_name='';              
        if(!username_exists($openId) ) {                         
            $new_user_data = apply_filters( 'new_user_data', array(
                'user_login'    => $openId,
                'first_name'	=> $nickname ,
                'nickname'      => $nickname,                    
                'user_nicename' => $_nickname,
                'display_name'  => $nickname,
                'user_pass'     => null,
                'user_email'    => $openId.'@weixin.com'
            ) );                
            $userId = wp_insert_user( $new_user_data );			
            if ( is_wp_error( $userId ) || empty($userId) ||  $userId==0 ) {
                return new WP_Error( 'error', '插入wordpress用户错误：', array( 'status' => 500 ) );				
            }

            update_user_meta($userId,'avatar',$avatarUrl);
            update_user_meta($userId,'usertype',"weixin");
            update_user_meta($userId,'unionId',$unionId);

        }            
        else{
            $user = get_user_by('login', $openId);
            $userId=   $user->ID;
            if(!empty($wxNickname) && $wxNickname !='微信用户')
            {                     
                $userdata =array(
                    'ID'            => $user->ID,
                    'first_name'	=> $nickname,
                    'nickname'      => $nickname,
                    'user_nicename' => $_nickname,
                    'display_name'  => $nickname
                );
                $userId =wp_update_user($userdata);
                if(is_wp_error($userId)){
                    return new WP_Error( 'error', '更新wp用户错误' , array( 'status' => 500 ) );
                }             
            }
            $display_name= $user->display_name;
            if(!empty($wxNickname) && $wxNickname !='微信用户')
            {
                if (delete_user_meta($userId, 'avatar') ) {
                    update_user_meta($userId,'avatar',$avatarUrl);
                }
        
            }
            
            if (delete_user_meta($userId, 'usertype') ) {
                $flag=update_user_meta($userId,'usertype',"weixin");
            }
            
            if(empty(get_user_meta( $userId, 'unionId', true )))
            {                  
                update_user_meta($userId,'unionId',$unionId);
            }
                
        }
        $userLevel= getUserLevel($userId);
        $enableUpdateAvatarCount= (int)getEnableUpdateAvatarCount($userId);
        $result["enableUpdateAvatarCount"]=$enableUpdateAvatarCount;  
        $result["code"]="success";            
        $result["message"]= "获取用户信息成功";
        $result["status"]="200";
        $result["openid"]=$openId;
        $result["nickname"]=$display_name;
        $result["avatarurl"]= get_user_meta($userId, 'avatar', true );
        $result["userLevel"]=$userLevel; 
        $result["userId"]=$userId; 
        $result["openid"]=$openId;     
        $result["storeappid"]=get_user_meta($userId, 'storeappid', true); 
        $result["storename"]=get_user_meta($userId, 'storename', true); 
        $result["storeinfo"]=get_user_meta($userId, 'storeinfo', true); 
        $response = rest_ensure_response($result);
        return $response; 
         
    }

    function sendmessage($request)
    {
      $openid= $request['openid'];
      $template_id=$request['template_id'];
      $postid=$request['postid'];
      $form_id=$request['form_id'];
      $total_fee=$request['total_fee'];
      $flag=$request['flag'];
      $fromUser =$request['fromUser'];
      $parent=0;
      if (isset($request['parent'])) {
          $parent =(int)$request['parent'];
      }

        $appid = get_option('wf_appid');
        $appsecret = get_option('wf_secret');
        $page='';
        if($flag =='1'  || $flag=='2' )
        {
            $total_fee= $total_fee.'元';
        }

        
        if($flag=='1' || $flag=='3' )
        {
            $page='pages/detail/detail?id='.$postid;

        }
        elseif($flag=='2')
        {
            $page='pages/about/about';
        }

        if(empty($appid) || empty($appsecret) )
        {
                $result["code"]="success";
                $result["message"]= "appid  or  appsecret is  empty";
                $result["status"]="500";                   
                return $result;
        }
        else
        {
        
            $access_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
            $access_result = https_request($access_url);
            if($access_result !="ERROR")
            {
                $access_array = json_decode($access_result,true);
                if(empty($access_array['errcode']))
                {
                    $access_token = $access_array['access_token']; 
                    $expires_in = $access_array['expires_in'];
                    $data = array();
                    $data1 = array(
                            "keyword1"=>array(
                            "value"=>$total_fee,                     
                             "color" =>"#173177"
                            ),
                            "keyword2"=>array(
                                "value"=>'谢谢你的赞赏,你的支持,是我前进的动力.',
                                "color"=> "#173177"
                            )
                        );  

                     date_default_timezone_set('PRC');
                     $datetime =date('Y-m-d H:i:s');
                     $data2 = array(
                            "keyword1"=>array(
                            "value"=>$fromUser,                     
                             "color" =>"#173177"
                            ),
                            "keyword2"=>array(
                                "value"=>$total_fee,
                                "color"=> "#173177"
                            ),
                            "keyword3"=>array(
                                "value"=>$datetime,
                                "color"=> "#173177"
                            )
                        );  


                    if($flag=='1' || $flag=='2' )
                    {
                        
                       $postdata['data']=$data1;

                    }
                    elseif ($flag=='3') {
                       
                        $postdata['data']=$data2;
                        
                    }

                    $postdata['touser']=$openid;
                    $postdata['template_id']=$template_id;
                    $postdata['page']=$page;
                    $postdata['form_id']=$form_id;
                    $postdata['template_id']=$template_id;
                    

                    $url ="https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token;

                    $access_result = $this->https_curl_post($url,$postdata,'json');

                    if($access_result !="ERROR"){
                        $access_array = json_decode($access_result,true);
                        if($access_array['errcode'] =='0')
                        {

                            
                            if($parent  !=0)
                            {
                                $delFlag=delete_comment_meta($parent,"formId",$form_id);
                                if($delFlag)
                                {
                                  $result["message"]= "发送消息成功(formId删除)";  
                                }
                                else
                                {
                                   $result["message"]= "发送消息成功(formId删除失败)"; 
                                }
                                
                            }
                            else
                            {
                                $result["message"]= "模板消息发送成功";
                            }
                            $result["code"]="success";
                            $result["status"]="200";                   
                            

                        }
                        else

                        {
                            $result["code"]=$access_array['errcode'];
                            $result["message"]= $access_array['errmsg'];
                            $result["status"]="500";
                            return $result;
                        }

                        
                    }
                    else{
                        $result["code"]="success";
                        $result["message"]= "https请求失败";
                        $result["status"]="500";                   
                        return $result;
                    }
                }               
                else
                {
                
                    $result["code"]=$access_array['errcode'];
                    $result["message"]= $access_array['errmsg'];
                    $result["status"]="500";
                    return $result;
                
                }
                
            }
            else
            {
                    $result["code"]="success";
                    $result["message"]= "https请求失败";
                    $result["status"]="500";
                    return $result;
            }
            
            
        }

        $response = rest_ensure_response( $result);
        return $response;


    }

    function https_curl_post($url,$data,$type){
        if($type=='json'){
            //$headers = array("Content-type: application/json;charset=UTF-8","Accept: application/json","Cache-Control: no-cache", "Pragma: no-cache");
            $data=json_encode($data);
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
        $data = curl_exec($curl);
        if (curl_errno($curl)){
            return 'ERROR';
        }
        curl_close($curl);
        return $data;
    }
 

    function getQrcodeImg($request)
    {
        $postId= $request['postid'];   
        $qrcode=creat_minapper_qrcode($postId);
        if($qrcode['errcode'] =='1')
        {
            $result["code"]="success";
            $result["message"]= "小程序码创建失败"; 
            $result["status"]="500"; 
        }
        else
        {
            
            $result["code"]="success";
            $result["message"]= "小程序码创建成功";
            $result["qrcodeimgUrl"]=$qrcode['qrcodeUrl']; 
            $result["status"]="200"; 
        }
        $response = rest_ensure_response( $result);
        return $response;   
        
    }

    function getWinxinQrcodeImg($request)
    {
        $postId= $request['postid'];   
        $qrcode=creat_minapper_qrcode($postId);
        if($qrcode['errcode'] =='1')
        {
            $result["code"]="success";
            $result["message"]= "小程序码创建失败"; 
            $result["status"]="500"; 
        }
        else
        {
            
            $result["code"]="success";
            $result["message"]= "小程序码创建成功";
            $result["qrcodeimgUrl"]=$qrcode['qrcodeUrl']; 
            $result["status"]="200"; 
        }
        $response = rest_ensure_response( $result);
        return $response;
      
    }

    function send_message_permissions_check($request)
    {
      $openid= $request['openid'];
      $template_id=$request['template_id'];
      $postid=$request['postid'];
      $form_id=$request['form_id'];
      $total_fee=$request['total_fee'];
      $flag=$request['flag'];
      $fromUser =$request['fromUser'];
      //$parent=(int)$request['parent'];

      

      if(empty($openid)  || empty($template_id) || empty($postid) || empty($form_id) || empty($total_fee) || empty($flag) || empty($fromUser))
      {
          return new WP_Error( 'error', '参数错误', array( 'status' => 500 ) );
      }
      else if(!function_exists('curl_init')) {
          return new WP_Error( 'error', 'php curl 
            扩展没有启用', array( 'status' => 500 ) );
      }
      return true;      
      
    }

    function get_qrcodeimg_permissions_check($request)
    {
        $postid= $request['postid'];      
            

        if(empty($postid))
        {
            return new WP_Error( 'error', '参数错误', array( 'status' => 500 ) );
        }
        else if(get_post($postid)==null)
        {
             return new WP_Error( 'error', 'postId参数错误', array( 'status' => 500 ) );
        }
        return true;
    }
    function  get_userInfo_permissions_check($request)
    {
        return true;
    }
  

    function update_user_wechatshop_info_permissions_check($request)
    {
        $openid= $request['openid'];
        $user=get_user_by('login',$openid);
        if(empty($user))
        {
            return new WP_Error( 'error', '参数错误', array( 'status' => 500 ) );
        }
        return true;
    }

    function get_openid_permissions_check($request)
    {
      $js_code= $request['js_code'];      
      if(empty($js_code))
      {
          return new WP_Error( 'error', 'js_code是空值', array( 'status' => 500 ) );
      }
      else if(!function_exists('curl_init')) {
          return new WP_Error( 'error', 'php  curl扩展没有启用', array( 'status' => 500 ) );
      }

      return true;
    }


}