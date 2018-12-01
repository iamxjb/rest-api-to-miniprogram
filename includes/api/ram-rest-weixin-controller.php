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
                    ),                    
                    'path' => array(
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


      if(empty($appid) || empty($appsecret) )
        {
                $result["code"]="success";
                $result["message"]= "appid  or  appsecret is  empty";
                $result["status"]="500"; 
        }
        else
        {
        
            $access_url = "https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$appsecret."&js_code=".$js_code."&grant_type=authorization_code";
            $access_result = https_request($access_url);
            if($access_result !="ERROR")
            {
                $access_array = json_decode($access_result,true);
                if(empty($access_array['errcode']))
                {
                    $openid = $access_array['openid']; 
                    $sessionKey = $access_array['session_key'];                    
                    // $pc = new WXBizDataCrypt($appid, $sessionKey);
                    // $errCode = $pc->decryptData($encryptedData, $iv, $data );
                    $errCode =decrypt_data($appid, $sessionKey,$encryptedData, $iv, $data);                   
                    if ($errCode == 0) {
                    
                        if(!username_exists($openid))
                        {
                            $data =json_decode($data,true);
                            //$unionId = $data['unionId'];
                            
                            $userdata = array(
                                'user_login'  =>  $openid,
                                'nickname'=> $nickname,
                                'user_nicename'=> $nickname,
                                'display_name' => $avatarUrl,
                                'user_pass'   =>  $openid 
                            );

                                $user_id = wp_insert_user( $userdata ) ;
                                if (is_wp_error( $user_id ) ) {
                                
                                    $result["code"]="success";
                                    $result["message"]= "生成用户失败";
                                    $result["status"]="500";
                                }
                                else
                                {
                                    $result["code"]="success";
                                    $result["message"]= "获取openid成功";
                                    $result["status"]="200";
                                    //$result["openid"]=$openid;
                                    //return $result;
                                }
                        
                        }
                        else
                        {
                            $result["code"]="success";
                            $result["message"]= "获取openid成功";
                            $result["status"]="200";
                            $result["openid"]=$openid;
                            //return $result;
                        }
                        
                    }
                    else {
                    
                        $result["code"]="success";
                        $result["message"]=$errCode;
                        $result["status"]="500";                   
                       
                        
                    } 
                    
                }               
                else
                {
                
                    $result["code"]=$access_array['errcode'];
                    $result["message"]= $access_array['errmsg'];
                    $result["status"]="500";                   
                    //return $result;
                
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
 

    function getWinxinQrcodeImg($request)
    {
        $postid= $request['postid'];      
        $path=$request['path'];
        $openid =$request['openid']; 

        $qrcodeName = 'qrcode-'.$postid.'.png';//文章小程序二维码文件名     
        $qrcodeurl = REST_API_TO_MINIPROGRAM_PLUGIN_DIR.'qrcode/'.$qrcodeName;//文章小程序二维码路径
        
        
        //自定义参数区域，可自行设置      
        $appid = get_option('wf_appid');
        $appsecret = get_option('wf_secret');
       
        //判断文章小程序二维码是否存在，如不存在，在此生成并保存
        if(!is_file($qrcodeurl)) {
            //$ACCESS_TOKEN = getAccessToken($appid,$appsecret,$access_token);
            $access_token_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;
             $access_token_result = https_request($access_token_url);
             if($access_token_result !="ERROR")
              {
                $access_token_array= json_decode($access_token_result,true);
                if(empty($access_token_array['errcode']))
                {
                  $access_token =$access_token_array['access_token'];
                  if(!empty($access_token))
                  {

                    //接口A小程序码,总数10万个（永久有效，扫码进入path对应的动态页面）
                    $url = 'https://api.weixin.qq.com/wxa/getwxacode?access_token='.$access_token;
                    //接口B小程序码,不限制数量（永久有效，将统一打开首页，可根据scene跟踪推广人员或场景）
                    //$url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$ACCESS_TOKEN;
                    //接口C小程序二维码,总数10万个（永久有效，扫码进入path对应的动态页面）
                    //$url = 'http://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token='.$ACCESS_TOKEN;

                    //header('content-type:image/png');
                    $color = array(
                        "r" => "0",  //这个颜色码自己到Photoshop里设
                        "g" => "0",  //这个颜色码自己到Photoshop里设
                        "b" => "0",  //这个颜色码自己到Photoshop里设
                    );
                    $data = array(
                        //$data['scene'] = "scene";//自定义信息，可以填写诸如识别用户身份的字段，注意用中文时的情况
                        //$data['page'] = "pages/index/index";//扫码后对应的path，只能是固定页面
                        'path' => $path, //前端传过来的页面path
                        'width' => intval(100), //设置二维码尺寸
                        'auto_color' => false,
                        'line_color' => $color,
                    );
                    $data = json_encode($data);
                    //可在此处添加或者减少来自前端的字段
                    $QRCode = get_content_post($url,$data);//小程序二维码
                    if($QRCode !='error')
                    {
                      //输出二维码
                      file_put_contents($qrcodeurl,$QRCode);
                      //imagedestroy($QRCode);
                      $flag=true;
                    }
                    
                  }
                  else
                  {
                    $flag=false;
                  }

                }
                else
                {
                  $flag=false;
                }

              }
              else
              {
                $flag=false;
              }
            
        }
        else
        {

          $flag=true;
        }

        if($flag)
        {
          $result["code"]="success";
            $result["message"]= "小程序码创建成功"; 
            $result["status"]="200"; 
            

        }
        else {
            $result["code"]="success";
            $result["message"]= "小程序码创建失败"; 
            $result["status"]="500"; 
            
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
        $path=$request['path'];
         

        if(empty($postid)  || empty($path))
        {
            return new WP_Error( 'error', '参数错误', array( 'status' => 500 ) );
        }
        else if(get_post($postid)==null)
        {
             return new WP_Error( 'error', 'postId参数错误', array( 'status' => 500 ) );
        }

        return true;
    }

    function get_openid_permissions_check($request)
    {
      $js_code= $request['js_code'];
      $encryptedData=$request['encryptedData'];
      $iv=$request['iv'];
      $avatarUrl=$request['avatarUrl'];
      $nickname=empty($request['nickname'])?'':$request['nickname'];
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