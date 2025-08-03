<?php 
//获取文章的第一张图片
function get_post_content_first_image($post_content){
    if(!$post_content){
        $the_post       = get_post();
        $post_content   = $the_post->post_content;
    } 

    preg_match_all( '/class=[\'"].*?wp-image-([\d]*)[\'"]/i', $post_content, $matches );
    if( $matches && isset($matches[1]) && isset($matches[1][0]) ){  
        $image_id = $matches[1][0];
        if($image_url = get_post_image_url($image_id)){
            return $image_url;
        }
    }

    preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', do_shortcode($post_content), $matches);
    if( $matches && isset($matches[1]) && isset($matches[1][0]) ){     
        return $matches[1][0];
    }
}

//获取文章图片的地址
function get_post_image_url($image_id, $size='full'){
    if($thumb = wp_get_attachment_image_src($image_id, $size)){
        return $thumb[0];
    }
    return false;   
}

function getPostImages($content,$postId){
    $content_first_image= get_post_content_first_image($content);

    
    $post_frist_image=$content_first_image;

    if (empty($post_frist_image) && !empty(get_option('wf_default_thumbnail_image'))) {
        $post_frist_image = get_option('wf_default_thumbnail_image');
    }

    // if(empty($content_first_image))
    // {
    //     $content_first_image='';
    // }

    // if(empty($post_frist_image))
    // {
    //     $post_frist_image='';
    // }

    $post_thumbnail_image_150='';
    $post_medium_image_300='';
    $post_thumbnail_image_624=''; 

    $post_thumbnail_image='';

    $post_medium_image="";
    $post_large_image="";
    $post_full_image="";   

    $_data =array();

    if (has_post_thumbnail($postId)) {
         //获取缩略的ID
         $thumbnailId = get_post_thumbnail_id($postId);
         //特色图缩略图
         $image = wp_get_attachment_image_src($thumbnailId, 'thumbnail');
         if($image !=false)
         {
             $post_thumbnail_image = $image[0];
             $post_thumbnail_image_150 = $image[0];
         }
         //特色中等图
         $image = wp_get_attachment_image_src($thumbnailId, 'medium');
         if($image !=false)
         {
             $post_medium_image = $image[0];
             $post_medium_image_300 = $image[0];
         }
         
         //特色大图
         $image = wp_get_attachment_image_src($thumbnailId, 'large');
         if($image !=false)
         {
             $post_large_image = $image[0];
             $post_thumbnail_image_624 = $image[0];
         }
         //特色原图
         $image = wp_get_attachment_image_src($thumbnailId, 'full');
         if($image !=false)
         {
             $post_full_image = $image[0];
         }

    }

    if(!empty($post_frist_image) && empty($post_thumbnail_image))
     {
        $post_thumbnail_image=$post_frist_image;
        $post_thumbnail_image_150=$post_frist_image;
     }

     if(!empty($post_frist_image) && empty($post_medium_image))
     {
        $post_medium_image=$post_frist_image;
        $post_medium_image_300=$post_frist_image;
        
     }

     if(!empty($post_frist_image) && empty($post_large_image))
     {
        $post_large_image=$post_frist_image;
        $post_thumbnail_image_624=$post_frist_image;
     }

     if(!empty($post_frist_image) && empty($post_full_image))
     {
        $post_full_image=$post_frist_image;
     }

     //$post_all_images = get_attached_media( 'image', $postId);
     $post_all_images= get_post_content_images($content);

     $_data['post_frist_image']=$post_frist_image;
     $_data['post_thumbnail_image']=$post_thumbnail_image;
     $_data['post_medium_image']=$post_medium_image;
     $_data['post_large_image']=$post_large_image;
     $_data['post_full_image']=$post_full_image;
     $_data['post_all_images']=$post_all_images;

     $_data['post_thumbnail_image_150']=$post_thumbnail_image_150;
     $_data['post_medium_image_300']=$post_medium_image_300;
     $_data['post_thumbnail_image_624']=$post_thumbnail_image_624;
    
    
    $_data['content_first_image']=$content_first_image; 


    return  $_data; 
           

}

function get_post_content_images($post_content){
    if(!$post_content){
        $the_post       = get_post();
        $post_content   = $the_post->post_content;
    } 

    

    preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', do_shortcode($post_content), $matches);
    $images=array();
    if($matches && isset($matches[1]))
    {
        $_images=$matches[1]; 
       
        for($i=0; $i<count($matches[1]);$i++) {
            $imageurl['imagesurl'] =$matches[1][$i];
            $imageurl['id'] ='image'.$i;
            $images[]=$imageurl;
            
        }
        
        return $images;

    }

    return null;
        
}


//等比例缩小图片，处理二维码
function PicCompress($src,$out_with=100){
    // 获取图片基本信息
    list($width, $height, $type, $attr) = getimagesize($src);
    // 获取图片后缀名
    $pictype = image_type_to_extension($type,false);
    // 拼接方法
    $imagecreatefrom = "imagecreatefrom".$pictype;
    // 打开传入的图片
    $in_pic = $imagecreatefrom($src);
    // 压缩后的图片长宽
    $new_width = $out_with;
    $new_height = $out_with/$width*$height;
    // 生成中间图片
    $temp = imagecreatetruecolor($new_width,$new_height);
    // 图片按比例合并在一起。
    imagecopyresampled($temp,$in_pic,0,0,0,0,$new_width,$new_height,$width,$height);
    // 销毁输入图片
    imagedestroy($in_pic);

    return $temp;

}

//添加文字到图片上，需要设置字体
function FontToPic($text,$font,$font_size=10,$pic_hight=50,$pic_width=300){
    // header("Content-type: image/jpeg");
    mb_internal_encoding("UTF-8");
    $im =imagecreate($pic_width,$pic_hight);
    $background_color = ImageColorAllocate ($im, 255, 255, 255);
    $col = imagecolorallocate($im, 0, 0, 0);
    $come=$text;
    /*水平居中（换行），固定字号*/
    $txt_max_width = intval(0.9*$pic_width);
    $content = "";
    for ($i=0;$i<mb_strlen($come);$i++) {
        $letter[] = mb_substr($come, $i, 1);
    }
    // var_dump($letter);die;
    foreach ($letter as $l) {
        $teststr = $content." ".$l;
        $testbox = imagettfbbox($font_size,0,$font,$teststr);
        // var_dump($testbox);die;
        // 判断拼接后的字符串是否超过预设的宽度
        if (($testbox[2] > $txt_max_width) && ($content !== "")) {
            $content .= "\n";
        }
        $content .= $l;
    }
    $test = explode("\n",$content);
    // var_dump($test);die;
    // $fbox = imagettfbbox(10,0,$font,$come);
    // echo  1;die;
    $txt_width = $testbox[2]-$testbox[0];

    $txt_height = $testbox[0]-$testbox[7];

    $y = ($pic_hight * 0.8)-((count($test)-1)*$txt_height); // baseline of text at 90% of $img_height
    // var_dump($txt_height);die;
    // imagettftext($im,$font_size,0,$x,$y,$col,$font,$content); //写 TTF 文字到图中
    foreach ($test as $key => $value) {
        $textbox = imagettfbbox($font_size,0,$font,$value);
        $txt_height = $textbox[0]-$textbox[7];
        $text_width = $textbox[2]-$textbox[0];
        $x = ($pic_width - $text_width) / 2;
        imagettftext($im,$font_size,0,$x,$y,$col,$font,$value);
        $y = $y+$txt_height+2; // 加2为调整行距
    }

    return $im;

}
/** 画圆角
 * @param $radius 圆角位置
 * @param $color_r 色值0-255
 * @param $color_g 色值0-255
 * @param $color_b 色值0-255
 * @return resource 返回圆角
 */
function get_lt_rounder_corner($radius, $color_r, $color_g, $color_b)
{
    // 创建一个正方形的图像
    $img = imagecreatetruecolor($radius, $radius);
    // 图像的背景
    $bgcolor = imagecolorallocate($img, $color_r, $color_g, $color_b);
    $fgcolor = imagecolorallocate($img, 0, 0, 0);
    imagefill($img, 0, 0, $bgcolor);
    // $radius,$radius：以图像的右下角开始画弧
    // $radius*2, $radius*2：已宽度、高度画弧
    // 180, 270：指定了角度的起始和结束点
    // fgcolor：指定颜色
    imagefilledarc($img, $radius, $radius, $radius * 2, $radius * 2, 180, 270, $fgcolor, IMG_ARC_PIE);
    // 将弧角图片的颜色设置为透明
    imagecolortransparent($img, $fgcolor);
    return $img;
}
/**
 * @param $im  大的背景图，也是我们的画板
 * @param $lt_corner 我们画的圆角
 * @param $radius  圆角的程度
 * @param $image_h 图片的高
 * @param $image_w 图片的宽
 */
function myradus($im, $lift, $top, $lt_corner, $radius, $image_h, $image_w)
{
/// lt(左上角)
    imagecopymerge($im, $lt_corner, $lift, $top, 0, 0, $radius, $radius, 100);
// lb(左下角)
    $lb_corner = imagerotate($lt_corner, 90, 0);
    imagecopymerge($im, $lb_corner, $lift, $image_h - $radius + $top, 0, 0, $radius, $radius, 100);
// rb(右上角)
    $rb_corner = imagerotate($lt_corner, 180, 0);
    imagecopymerge($im, $rb_corner, $image_w + $lift - $radius, $image_h + $top - $radius, 0, 0, $radius, $radius, 100);
// rt(右下角)
    $rt_corner = imagerotate($lt_corner, 270, 0);
    imagecopymerge($im, $rt_corner, $image_w - $radius + $lift, $top, 0, 0, $radius, $radius, 100);
}
//需要填写AppId和AppSecret
// function getAccessToken($appid,$appsecret) {
//     $AppId = $appid; //小程序APPid
//     $AppSecret = $appsecret; //小程序APPSecret
//     $data = json_decode(file_get_contents("access_token.json"));
//     if ($data->expire_time < time()) {
//         $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$AppId.'&secret='.$AppSecret;
//         $res = json_decode(httpGet($url));
//         $access_token = $res->access_token;
//         if ($access_token) {
//             $data->expire_time = time() + 7000;
//             $data->access_token = $access_token;
//             $fp = fopen("access_token.json", "w");
//             fwrite($fp, json_encode($data));
//             fclose($fp);
//         }
//     } else {
//        $access_token = $data->access_token;
//     }
//       return $access_token;
// }

function get_content_post($url,$post_data=array(),$header=array()){
    // phpcs:disable WordPress.WP.AlternativeFunctions.curl_curl_init
		// phpcs:disable WordPress.WP.AlternativeFunctions.curl_curl_setopt
		// phpcs:disable WordPress.WP.AlternativeFunctions.curl_curl_exec
		// phpcs:disable WordPress.WP.AlternativeFunctions.curl_curl_close
        // phpcs:disable WordPress.WP.AlternativeFunctions.curl_curl_getinfo
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_AUTOREFERER,true);
    $content = curl_exec($ch);
    $info = curl_getinfo($ch,CURLINFO_EFFECTIVE_URL);
    $code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
    curl_close($ch);
    // phpcs:enable WordPress.WP.AlternativeFunctions.curl_curl_init
		// phpcs:enable WordPress.WP.AlternativeFunctions.curl_curl_setopt
		// phpcs:enable WordPress.WP.AlternativeFunctions.curl_curl_exec
		// phpcs:enable WordPress.WP.AlternativeFunctions.curl_curl_close
        //phpcs:enable WordPress.WP.AlternativeFunctions.curl_curl_getinfo

    if ($code == '200') {
        $errcode = 0;
    } else {
        $errcode = (int)$code;
    }
    $result =  array('code' => $code, 'errcode' => $errcode, 'buffer' => $content);
    return $result;
}

//发起https请求
function https_request($url)
{
    // phpcs:disable WordPress.WP.AlternativeFunctions.curl_curl_init
		// phpcs:disable WordPress.WP.AlternativeFunctions.curl_curl_setopt
		// phpcs:disable WordPress.WP.AlternativeFunctions.curl_curl_exec
		// phpcs:disable WordPress.WP.AlternativeFunctions.curl_curl_close
        //phpcs:disable WordPress.WP.AlternativeFunctions.curl_curl_errno
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl,  CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);  
    $data = curl_exec($curl);
    if (curl_errno($curl)){
        return 'ERROR';
    }
    curl_close($curl);
    // phpcs:enable WordPress.WP.AlternativeFunctions.curl_curl_init
		// phpcs:enable WordPress.WP.AlternativeFunctions.curl_curl_setopt
		// phpcs:enable WordPress.WP.AlternativeFunctions.curl_curl_exec
		// phpcs:enable WordPress.WP.AlternativeFunctions.curl_curl_close
        //phpcs:enable WordPress.WP.AlternativeFunctions.curl_curl_errno
    return $data;
}


function https_curl_post($url,$data,$type){
        if($type=='json'){
            //$headers = array("Content-type: application/json;charset=UTF-8","Accept: application/json","Cache-Control: no-cache", "Pragma: no-cache");
            $data=json_encode($data);
        }
        // phpcs:disable WordPress.WP.AlternativeFunctions.curl_curl_init
		// phpcs:disable WordPress.WP.AlternativeFunctions.curl_curl_setopt
		// phpcs:disable WordPress.WP.AlternativeFunctions.curl_curl_exec
		// phpcs:disable WordPress.WP.AlternativeFunctions.curl_curl_close
        // phpcs:disable WordPress.WP.AlternativeFunctions.curl_curl_errno
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
        // phpcs:enable WordPress.WP.AlternativeFunctions.curl_curl_init
		// phpcs:enable WordPress.WP.AlternativeFunctions.curl_curl_setopt
		// phpcs:enable WordPress.WP.AlternativeFunctions.curl_curl_exec
		// phpcs:enable WordPress.WP.AlternativeFunctions.curl_curl_close
        //phpcs:enable WordPress.WP.AlternativeFunctions.curl_curl_errno
        return $data;
    }


function time_tran($the_time){
         //phpcs:disable WordPress.DateTime.RestrictedFunctions.timezone_change_date_default_timezone_set
      date_default_timezone_set('Asia/Shanghai');
      //phpcs:enable WordPress.DateTime.RestrictedFunctions.timezone_change_date_default_timezone_set
    $now_time = gmdate("Y-m-d H:i:s",time()); 
    $now_time = strtotime($now_time);
    $show_time = strtotime($the_time);
    $dur = $now_time - $show_time;
    if($dur < 0){
        return $the_time; 
    }else{
        if($dur < 60){
            return $dur.'秒前'; 
        }else{
            if($dur < 3600){
             return floor($dur/60).'分钟前'; 
         }
         else{
                 if($dur < 86400){
                     return floor($dur/3600).'小时前'; 
                 }
                 else{
                   if($dur < 259200){//3天内
                     return floor($dur/86400).'天前';
                    }
                     else{
                         return gmdate("Y-m-d",$show_time); 
                     }
                }
            }
        }
    }
}

/**
 * 检验数据的真实性，并且获取解密后的明文.
 * @param $sessionKey string 用户在小程序登录后获取的会话密钥
 * @param $appid string 小程序的appid
 * @param $encryptedData string 加密的用户数据
 * @param $iv string 与用户数据一同返回的初始向量
 * @param $data string 解密后的原文
 *
 * @return int 成功0，失败返回对应的错误码
 */
function raw_decrypt_data( $appid, $sessionKey, $encryptedData, $iv, &$data ) {
    
    $errors = array(
        'OK'                => 0,
        'IllegalAesKey'     => -41001,
        'IllegalIv'         => -41002,
        'IllegalBuffer'     => -41003,
        'DecodeBase64Error' => -41004
    );
    
    if (strlen($sessionKey) != 24)
    {
        return $errors['IllegalAesKey'];
    }
    $aesKey=base64_decode($sessionKey);

    
    if (strlen($iv) != 24)
    {
        return $errors['IllegalIv'];
    }
    $aesIV=base64_decode($iv);

    $aesCipher=base64_decode($encryptedData);

    $result=openssl_decrypt( $aesCipher, 'AES-128-CBC', $aesKey, 1, $aesIV);

    $dataObj=json_decode( $result );
    if( $dataObj  == NULL )
    {
        return $errors['IllegalBuffer'];
    }
    if( $dataObj->watermark->appid != $appid )
    {
        return $errors['IllegalBuffer'];
    }
    $data = $result;
    return $errors['OK'];
}

function ram_get_client_ip()
{
    foreach (array(
                'HTTP_CLIENT_IP',
                'HTTP_X_FORWARDED_FOR',
                'HTTP_X_FORWARDED',
                'HTTP_X_CLUSTER_CLIENT_IP',
                'HTTP_FORWARDED_FOR',
                'HTTP_FORWARDED',
                'REMOTE_ADDR') as $key) {
        if (array_key_exists($key, $_SERVER)) {
            // phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash
            // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitize
            // phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                // phpcs:enable WordPress.Security.ValidatedSanitizedInput.MissingUnslash
                // phpcs:enable WordPress.Security.ValidatedSanitizedInput.InputNotSanitize
                // phpcs:enable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                $ip = trim($ip);
                //会过滤掉保留地址和私有地址段的IP，例如 127.0.0.1会被过滤
                //也可以修改成正则验证IP
                if ((bool) filter_var($ip, FILTER_VALIDATE_IP,
                                FILTER_FLAG_IPV4 |
                                FILTER_FLAG_NO_PRIV_RANGE |
                                FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
    }
    return null;
}

function filterEmoji($nickname){
    $nickname = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $nickname);
    $nickname = preg_replace('/[\x{1F300}-\x{1F5FF}]/u', '', $nickname);
    $nickname = preg_replace('/[\x{1F680}-\x{1F6FF}]/u', '', $nickname);
    $nickname = preg_replace('/[\x{2600}-\x{26FF}]/u', '', $nickname);
    $nickname = preg_replace('/[\x{2700}-\x{27BF}]/u', '', $nickname);
    $nickname = str_replace(array('"','\''), '', $nickname);
    $nickname = preg_replace_callback( '/./u',
      function (array $match) {
        return strlen($match[0]) >= 4 ? '' : $match[0];
      },
      $nickname);
    return addslashes(trim($nickname));
}

function  getUserLevel($userId)
{
    global $wpdb;
    
// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
    // phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
    $level =$wpdb->get_var($wpdb->prepare("SELECT  t.meta_value
            FROM
                ".$wpdb->usermeta." t
            WHERE
                t.meta_key = '". $wpdb->prefix."user_level' 
            AND t.user_id =%d",$userId)); 
    // phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
    // phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
    $levelName ="订阅者";
    switch($level)
    {
        case "10":
        $levelName="管理者";
        break;

        case "7":
        $levelName="编辑";
        break;

        case "2":
        $levelName="作者";
        break;

        case "1":
        $levelName="贡献者";
        break;

        case "0":
        $levelName="订阅者";
        break;

    }
    $userLevel["level"]=$level;
    $userLevel["levelName"]=$levelName;
    return $userLevel;

}

// function get_post_qq_video($content)
// {
//     $vcontent ='';
//     preg_match('/https\:\/\/v.qq.com\/x\/(\S*)\/(\S*)\.html/',$content,$matches);
//     if($matches)
//     {
//     	$vids=$matches[2];
// 	    //$url='http://vv.video.qq.com/getinfo?vid='.$vids.'&defaultfmt=auto&otype=json&platform=1&defn=fhd&charge=0';
// 	    //  defaultfmt： 1080P-fhd，超清-shd，高清-hd，标清-sd
// 	    $url='http://vv.video.qq.com/getinfo?vid='.$vids.'&defaultfmt=auto&otype=json&platform=11001&defn=fhd&charge=0';
// 	    //$res = file_get_contents($url);
//         $res = https_request($url);
// 	    if($res)
// 	    {
// 	    	$str = substr($res,13,-1);
// 		    $newStr =json_decode($str,true);	    
// 		    //$videoUrl= $newStr['vl']['vi'][0]['ul']['ui'][2]['url'].$newStr['vl']['vi'][0]['fn'].'?vkey='.$newStr['vl']['vi'][0]['fvkey']; 
// 		    $videoUrl= $newStr['vl']['vi'][0]['ul']['ui'][0]['url'].$newStr['vl']['vi'][0]['fn'].'?vkey='.$newStr['vl']['vi'][0]['fvkey']; 
// 		    $vcontent = preg_replace('~<video (.*?)></video>~s','<video src="'.$videoUrl.'" controls="controls" width="100%"></video>',$content);
        
//         }	    
	    
//     }

//     return $vcontent;
// }

function get_post_qq_video($content)
{
    $vcontent ='';
    preg_match('/https\:\/\/v.qq.com\/x\/(\S*)\/(\S*)\.html/',$content,$matches);
    if($matches)
    {
        $vids=$matches[2];
        $vcontent = preg_replace('~<video (.*?)></video>~s', '<iframe frameborder="0" src="https://v.qq.com/txp/iframe/player.html?vid=' . $vids . '" allowFullScreen="true" width="100%" height="500px"></iframe>', $content);
        //$videoUrl= get_qq_video_url($vids);
        //$vcontent = preg_replace('~<video (.*?)></video>~s','<video src="'.$videoUrl.'" poster="https://puui.qpic.cn/qqvideo_ori/0/'.$vids.'_496_280/0" controls="controls" width="100%"></video>',$content);	    
	    
    }

    return $vcontent;
}

function get_qq_video_url($vid)
{
    $url = 'https://vv.video.qq.com/getinfo?vids='.$vid.'&platform=101001&charge=0&otype=json';
    $json = file_get_contents($url);
    preg_match('/^QZOutputJson=(.*?);$/',$json,$json2);
    $tempStr = json_decode($json2[1],true);
    $vurl = 'https://ugcws.video.gtimg.com/'.$tempStr['vl']['vi'][0]['fn']."?vkey=".$tempStr['vl']['vi'][0]['fvkey'];
    return $vurl;
}

function get_post_content_audio($post_content){
    if(!$post_content){
        $the_post       = get_post();
        $post_content   = $the_post->post_content;
    }
    $list = array(); 
    $c1 = preg_match_all('/<audio\s.*?>/', do_shortcode($post_content), $m1);  //先取出所有img标签文本  
    for($i=0; $i<$c1; $i++) {    //对所有的img标签进行取属性  
        $c2 = preg_match_all('/(\w+)\s*=\s*(?:(?:(["\'])(.*?)(?=\2))|([^\/\s]*))/', $m1[0][$i], $m2);   //匹配出所有的属性  
        for($j=0; $j<$c2; $j++) {    //将匹配完的结果进行结构重组  
            $list[$i][$m2[1][$j]] = !empty($m2[4][$j]) ? $m2[4][$j] : $m2[3][$j];  
        }  
    } 
    

    return $list;
        
}

function get_content_gallery($content,$flag){    
    $list = array();
    //$content=self::nl2p($content,true,false);//把换行转换成p标签
    if($flag)
    {
        $content=nl2br($content);
    }    
    $vcontent=$content;

    $c1 = preg_match_all('|\[gallery.*?ids=[\'"](.*?)[\'"].*?\]|i',$content, $m1);  //先取出所有gallery短代码
    for($i=0; $i<$c1; $i++) {    //对所有的img标签进行取属性  
        $c2 = preg_match_all('/(\w+)\s*=\s*(?:(?:(["\'])(.*?)(?=\2))|([^\/\s]*))/', $m1[0][$i], $m2);   //匹配出所有的属性  
        for($j=0; $j<$c2; $j++) {    //将匹配完的结果进行结构重组  
            $list[$i][$m2[1][$j]] = !empty($m2[4][$j]) ? $m2[4][$j] : $m2[3][$j];  
        }  
    } 
    
    $ids =$list[0]['ids'];
    if(!empty($ids))
    {
        $ids =explode(',',$ids);
        $img = '';
        $realwidth = '';
        $real_height = '';
        $i = 0;
        foreach($ids as $id)
        {
            $image=wp_get_attachment_image_src((int)$id,'full');
            $img = $i == 0 ? $img . $image[0] : $img . ',' . $image[0];
            $realwidth = $i == 0 ? $realwidth . $image[1] : $realwidth . ',' . $image[1];
            $real_height = $i == 0 ? $real_height . $image[2] : $real_height . ',' . $image[2];
            $i++;
            //$img .='<img width="'.$image[1].'" height="'.$image[2].'" src="'.$image[0].'" />';
           

        }
        $minappergallery = '<minappergallery images="' . $img . '"  real-width="' . $realwidth . '"  real_height="' . $real_height . '">';
        $vcontent = preg_replace('~\[gallery (.*?)\]~s', $minappergallery, $content);
        //$vcontent = preg_replace('~\[gallery (.*?)\]~s',$img,$content);
        

    }

    return $vcontent;
        
}

function  getPosts($ids)
    {
        global $wpdb;
        // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
    // phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
        $_posts = $wpdb->get_results( $wpdb->prepare("SELECT *  from ".$wpdb->posts." where id in(%s) ORDER BY find_in_set(id,%s)",$ids,$ids));
        // phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
    // phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
        $posts =array(); 
        if(!empty($_posts))  
        {
            foreach ($_posts as $post) {    
                $post_id = (int) $post->ID;
                $post_title = stripslashes($post->post_title);
                $post_content=  nl2br($post->post_content);              
                $post_date =$post->post_date;
                $post_permalink = get_permalink($post->ID);            
                $_data["id"]  =$post_id;
                $_data["post_title"] =$post_title;
                $_data["post_content"] =$post_content;                
                $_data["post_date"] =$post_date; 
                $_data["post_permalink"] =$post_permalink;
                $_data['type']="detailpage";  
           
                $enterpriseMinapp=get_option('wf_enterprise_minapp'); 
                $enterpriseMinapp=empty($enterpriseMinapp)?'0':$enterpriseMinapp;
                $_data['enterpriseMinapp']=$enterpriseMinapp;

                $praiseWord=get_option('wf_praise_word'); 
                $praiseWord=empty($praiseWord)?'鼓励':$praiseWord;
                $_data['praiseWord']=$praiseWord;
                
                $pageviews = (int) get_post_meta( $post_id, 'wl_pageviews',true);
                $_data['pageviews'] = $pageviews;
                // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
    // phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
                $comment_total = $wpdb->get_var($wpdb->prepare("SELECT COUNT(1) FROM ".$wpdb->comments." where  comment_approved = '1' and comment_post_ID=%d",$post_id));
                // phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
    // phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
                $_data['comment_total']= $comment_total;
    
                $images =getPostImages($post->post_content,$post_id);         
                
                $_data['post_thumbnail_image']=$images['post_thumbnail_image'];
                $_data['content_first_image']=$images['content_first_image'];
                $_data['post_medium_image_300']=$images['post_medium_image_300'];
                $_data['post_thumbnail_image_624']=$images['post_thumbnail_image_624'];
    
                $_data['post_frist_image']=$images['post_frist_image'];
                $_data['post_medium_image']=$images['post_medium_image'];
                $_data['post_large_image']=$images['post_large_image'];
                $_data['post_full_image']=$images['post_full_image'];
                $_data['post_all_images']=$images['post_all_images'];
                $posts[] = $_data;
            }  

        }
        return $posts;        

    }

    function custom_minapper_post_fields( $_data, $post, $request) { 

        global $wpdb;       
        $post_id =$post->ID;    
        //$content =get_the_content();
        $content=html_entity_decode($_data['content']['rendered']);
        $content_protected=$_data['content']['protected'];
        $raw=empty($_data['content']['raw'])?'':$_data['content']['raw'];
    
       
         
        $siteurl = get_option('siteurl');
        $upload_dir = wp_upload_dir();
        $content = str_replace( 'http:'.strstr($siteurl, '//'), 'https:'.strstr($siteurl, '//'), $content);
        $content = str_replace( 'http:'.strstr($upload_dir['baseurl'], '//'), 'https:'.strstr($upload_dir['baseurl'], '//'), $content);
        
        $images =getPostImages($content, $post_id); 
        $_data['post_thumbnail_image']=$images['post_thumbnail_image'];
        $_data['content_first_image']=$images['content_first_image'];
        $_data['post_medium_image_300']=$images['post_medium_image_300'];
        $_data['post_thumbnail_image_624']=$images['post_thumbnail_image_624'];
    
        $_data['post_frist_image']=$images['post_frist_image']; 
        $_data['post_medium_image']=$images['post_medium_image'];
        $_data['post_large_image']=$images['post_large_image'];
        $_data['post_full_image']=$images['post_full_image'];
        $_data['post_all_images']=$images['post_all_images'];
    
         //获取广告参数
    
      $videoAdId=empty(get_option('wf_video_ad_id'))?'':get_option('wf_video_ad_id');
      $_data['videoAdId']=$videoAdId;
      
      $listAdId=empty(get_option('wf_list_ad_id'))?'':get_option('wf_list_ad_id');
      $listAd=empty(get_option('wf_list_ad'))?'0':"1"; 
      $listAdEvery=empty(get_option('wf_list_ad_every'))?5:(int)get_option('wf_list_ad_every');
    
      
    
      $_data['listAd']=$listAd;
      $_data['listAdId']=$listAdId;
      $_data['listAdEvery']=$listAdEvery;
    
        $comments_count = wp_count_comments($post_id);    
        $_data['total_comments']=$comments_count->approved;
        $category =get_the_category($post_id);
        if(!empty($category))
        {
          $_data['category_name'] =$category[0]->cat_name; 
        }
        
        $post_date =$post->post_date;
        //$_data['date'] =time_tran($post_date);
        $_data['post_date'] =time_tran($post_date);
// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
    // phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
        $like_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(1) FROM ".$wpdb->postmeta." where meta_value='like' and post_id=%d",$post_id));
        // phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
    // phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
        $_data['like_count']= $like_count; 
        $post_views = (int)get_post_meta($post_id, 'wl_pageviews', true);     
        $params = $request->get_params();
         if ( isset( $params['id'] ) ) {


             //获取推荐商品（微信小店商品）
          $_data['recommendWechatShopGoods'] = getRecommendWechatShopGoods($post_id);
          
          $post_year =gmdate('Y',strtotime($post_date));
          $post_month =gmdate('m',strtotime($post_date));
          $post_day =gmdate('d',strtotime($post_date));
          $history_post_single = get_history_post_list($post_year,$post_month,$post_day);
          $_data['history_post_single']=$history_post_single;
      



          
          $praiseWord=get_option('wf_praise_word'); 
          $praiseWord=empty($praiseWord)?'鼓励':$praiseWord;
          $_data['praiseWord']=$praiseWord;

          $copyright_state=empty(get_option('wf_copyright_state'))?'':get_option('wf_copyright_state'); 
          $_data['copyright_state']=$copyright_state;
    
          //获取广告参数
          $detailAdId=empty(get_option('wf_detail_ad_id'))?'':get_option('wf_detail_ad_id');
          $detailAd=empty(get_option('wf_detail_ad'))?'0':"1";
    
          $rewardedVideoAdId=empty(get_option('wf_excitation_ad_id'))?'':get_option('wf_excitation_ad_id');
          $excitationAd = empty(get_post_meta($post_id, '_excitation', true))?"0":get_post_meta($post_id, '_excitation', true);
    
          $_data['excitationAd']=$excitationAd;
          $_data['rewardedVideoAdId']=$rewardedVideoAdId;
    
          $_data['detailAdId']=$detailAdId;
          $_data['detailAd']=$detailAd;
          
          $enterpriseMinapp=get_option('wf_enterprise_minapp'); 
          $enterpriseMinapp=empty($enterpriseMinapp)?'0':$enterpriseMinapp;
          
          
          $_data['enterpriseMinapp']=$enterpriseMinapp;
            $vcontent =get_post_qq_video($content);//解析腾讯视频
            if(!empty($vcontent))
            {
               $content=$vcontent;
            }
    
            //解析音频
          $audios=  get_post_content_audio($post->post_content);
          $_data['audios']=$audios;
    
            // $sql="select post_content from ".$wpdb->posts." where id=".$post_id;
            // $postContent = $wpdb->get_var($sql);
            // // if(has_shortcode($postContent, 'gallery' ))//处理内容里的相册显示
            // // {
            // //   $content= get_content_gallery($postContent,true);
            // // }
            $_content['rendered'] =$content;
            $_content['raw'] =$raw;//古腾堡编辑器需要该属性，否则报错
            $_content['protected'] =$content_protected;  
            $_data['content']= $_content;
    
    
            $postImageUrl=get_option("wf_poster_imageurl");
            $_data['postImageUrl']= $postImageUrl;
    
    
    
          // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
    // phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
            $likes = $wpdb->get_results($wpdb->prepare("SELECT meta_key , (SELECT id from ".$wpdb->users." WHERE user_login=substring(meta_key,2)) as id ,(SELECT display_name from ".$wpdb->users." WHERE user_login=substring(meta_key,2)) as display_name  FROM ".$wpdb->postmeta." where meta_value='like' and post_id=%d",$post_id));
            // phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
    // phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
            $avatarurls =array();
            foreach ($likes as $like) {
                $userId = $like->id;                
                $avatar= get_user_meta( $userId, 'avatar', true );
                
                if(!empty($avatar))
                {
                  $_avatarurl['avatarurl']  =$avatar;
                 
    
                }
                else{
                  $avatar = plugins_url()."/".REST_API_TO_MINIPROGRAM_PLUGIN_NAME."/includes/images/gravatar.png";
                  $_avatarurl['avatarurl']  =$avatar;
                }
                $avatarurls[] = $_avatarurl; 
                       
            }
          $post_views =$post_views+1;  
          if(!update_post_meta($post_id, 'wl_pageviews', $post_views))   
          {  
            add_post_meta($post_id, 'wl_pageviews', 1, true);  
          } 
          $_data['avatarurls']= $avatarurls;
               //phpcs:disable WordPress.DateTime.RestrictedFunctions.timezone_change_date_default_timezone_set
      date_default_timezone_set('Asia/Shanghai');
      //phpcs:enable WordPress.DateTime.RestrictedFunctions.timezone_change_date_default_timezone_set
          $fristday= gmdate("Y-m-d H:i:s", strtotime("-1 year"));
          $today = gmdate("Y-m-d H:i:s"); //获取今天日期时间
          $tags= $_data["tags"];
            if(!empty($tags))
            {
              $tags=implode(",",$tags);
            //   $sql="
            //   SELECT distinct ID, post_title
            //   FROM ".$wpdb->posts." , ".$wpdb->term_relationships.", ".$wpdb->term_taxonomy."
            //   WHERE ".$wpdb->term_taxonomy.".term_taxonomy_id =  ".$wpdb->term_relationships.".term_taxonomy_id
            //   AND ID = object_id
            //   AND taxonomy = 'post_tag'
            //   AND post_status = 'publish'
            //   AND post_type = 'post'
            //   AND term_id IN (" . $tags . ")
            //   AND ID != '" . $post_id . "'
            //   AND post_date BETWEEN '".$fristday."' AND '".$today."' 
            //   ORDER BY  RAND()
            //   LIMIT 5";
            // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
    // phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
              $related_posts = $wpdb->get_results( $wpdb->prepare("
              SELECT distinct ID, post_title
              FROM ".$wpdb->posts." , ".$wpdb->term_relationships.", ".$wpdb->term_taxonomy."
              WHERE ".$wpdb->term_taxonomy.".term_taxonomy_id =  ".$wpdb->term_relationships.".term_taxonomy_id
              AND ID = object_id
              AND taxonomy = 'post_tag'
              AND post_status = 'publish'
              AND post_type = 'post'
              AND term_id IN (%d)
              AND ID != %s
              AND post_date BETWEEN %s AND %s
              ORDER BY  RAND()
              LIMIT 5",$tags,$post_id,$fristday,$today));
// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
// phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
    
              $_data['related_posts'] = $related_posts;
    
            }
            else{
              $_data['related_posts']=null;
            }
            
            
        }
        else 
        {
            unset($_data['content'] );   
            
        }
        $pageviews =$post_views ;   
        $_data['pageviews'] = $pageviews;
        if(!empty($category))
        {
    
          $category_id=$category[0]->term_id;
          $next_post = get_next_post($category_id, '', 'category');
          $previous_post = get_previous_post($category_id, '', 'category');
          $_data['next_post_id'] = !empty($next_post->ID)?$next_post->ID:null;
          $_data['next_post_title'] = !empty($next_post->post_title)?$next_post->post_title:null;
          $_data['previous_post_id'] = !empty($previous_post->ID)?$previous_post->ID:null;
          $_data['previous_post_title'] = !empty($previous_post->post_title)?$previous_post->post_title:null;
    
        }   
        // $data->data = $_data;     
        return $_data; 
    }

    //重写内容
    function ram_rewrite_content($content, $num)
    {
       
        $tempContent = substr($content, 0, $num);
        if (strlen($tempContent) == strlen($content))
            return $content;

        else {
            if (($post_content = strrpos($tempContent, "<")) > strrpos($tempContent, ">"))
                $tempContent = substr($tempContent, 0, $post_content);
            return '' . strip_tags(ram_utf8_trim($tempContent), '<br/>') . '......';
        }
    }

    function ram_utf8_trim($str)
    {

        $len = strlen($str);
        $hex = '';
        for ($i = strlen($str) - 1; $i >= 0; $i -= 1) {
            $hex .= ' ' . ord($str[$i]);
            $ch = ord($str[$i]);
            if (($ch & 128) == 0) return (substr($str, 0, $i));
            if (($ch & 192) == 192) return (substr($str, 0, $i));
        }
        return ($str . $hex);
    }

    function ram_randString()
    {
        $code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rand = $code[wp_rand(0,25)]
            .strtoupper(dechex(gmdate('m')))
            .gmdate('d').substr(time(),-5)
            .substr(microtime(),2,5)
            .sprintf('%02d',wp_rand(0,99));
        for(
            $a = md5( $rand, true ),
            $s = '0123456789abcdefjhijklmnopqrstuv',
            $d = '',
            $f = 0;
            $f < 8;
            $g = ord( $a[ $f ] ),
            $d .= $s[ ( $g ^ ord( $a[ $f + 8 ] ) ) - $g & 0x1F ],
            $f++
        );
        return  $d;
    }

    function ram_strLength($str,$charset='utf-8'){  
        if($charset=='utf-8') $str = iconv('utf-8','gb2312',$str);  
        $num = strlen($str);  
        $cnNum = 0;  
        for($i=0;$i<$num;$i++){  
        if(ord(substr($str,$i+1,1))>127){  
        $cnNum++;  
        $i++;  
        }  
        }  
        $enNum = $num-($cnNum*2);  
        $number = ($enNum/2)+$cnNum;  
        return ceil($number);  
    }

     function security_msgSecCheck($data){
          
        $msgSecCheckResult = RAM()->wxapi->msgSecCheck($data);
		$errcode=$msgSecCheckResult['errcode'];		
		$errmsg=$msgSecCheckResult['errmsg'];
        $result=array();
		if($errcode!=0)
		{
            $result['errcode']=$errcode;
            $result['errmsg']=$errmsg;
			
		}
        else
        {
            $checkResult=$msgSecCheckResult['result'];
            $label=$checkResult['label'];
            if($label !='100')
            {
                $result['errcode']=(int)$label;
                $result['errmsg']="内容无法通过审核";
                
            }
            else
            {
                $result['errcode']=0;
                $result['errmsg']="内容合规合法";
            }
        }
        return  $result;
    }

    function jscode2session($js_code){
          
            $appid = get_option('wf_appid');
            $appsecret = get_option('wf_secret');
            $api_result=array();
            if(empty($appid) || empty($appsecret) ){
                $api_result['errcode']="3";
                $api_result['errmsg']="appid或appsecret为空";
                //return new WP_Error( 'error', 'appid或appsecret为空', array( 'status' => 500 ) );
            }
            $access_url = "https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$appsecret."&js_code=".$js_code."&grant_type=authorization_code";
            $access_result = https_request($access_url);
            
            if($access_result=='ERROR') {

                $api_result['errcode']="1";
                $api_result['errmsg']=json_encode($access_result);

                return $api_result;
                //return new WP_Error( 'error', 'API错误：' . json_encode($access_result), array( 'status' => 501 ) );
            } 
            $api_result  = json_decode($access_result,true);            
            if( empty( $api_result['openid'] ) || empty( $api_result['session_key'] )) {
                $api_result['errcode']="2";
                $api_result['errmsg']=json_encode( $api_result );
                return $api_result;
               // return new WP_Error('error', 'API错误：' . json_encode( $api_result ), array( 'status' => 502 ) );
            }            
            $api_result['errcode']="0";
            $api_result['errmsg']="获取成功";              
            return $api_result;


    }

    //获取可修改头像的次数
    function getEnableUpdateAvatarCount($userId){
        $year=gmdate('Y', time());
        $updateAvatarCount=$year."-"."updateAvatarCount";
        $updateCount =empty(get_user_meta($userId,$updateAvatarCount))?0:(int)get_user_meta($userId,$updateAvatarCount,true);
        $configCount =(int)get_option('wf_updateAvatar_count');
        $count=$configCount-$updateCount;
        if($count <0)
        {
            $count;
        }
        return $count;
    }

    //获取修改头像的次数
    function getUpdateAvatarCount($userId){
        $year=gmdate('Y', time());
        $updateAvatarCount=$year."-"."updateAvatarCount";
        $updateCount =empty(get_user_meta($userId,$updateAvatarCount))?0:(int)get_user_meta($userId,$updateAvatarCount,true);
        return  $updateCount;
    }

    //设置修改头像的次数
    function setUpdateAvatarCount($userId,$count){
        $year=gmdate('Y', time());
        $updateAvatarCount=$year."-"."updateAvatarCount";
        update_user_meta($userId,$updateAvatarCount,$count);
    }

    function creat_minapper_qrcode($postId)
    {
        $path ="pages/detail/detail?id=".$postId;
        $qrcodeName = 'qrcode-'.$postId.'.png';//文章小程序二维码文件名     
        $qrcodePath = REST_API_TO_MINIPROGRAM_PLUGIN_DIR.'qrcode/';//文章小程序二维码路径
        $qrcodeUrl = plugins_url().'/'.REST_API_TO_MINIPROGRAM_PLUGIN_NAME.'/qrcode/'.$qrcodeName;
        if (!is_dir($qrcodePath)) 
        {
            // phpcs:disable WordPress.WP.AlternativeFunctions.file_system_operations_mkdir
            mkdir($qrcodePath, 0777);
            // phpcs:enable WordPress.WP.AlternativeFunctions.file_system_operations_mkdir
        }

        $qrcodePath = REST_API_TO_MINIPROGRAM_PLUGIN_DIR.'qrcode/'.$qrcodeName;//文章小程序二维码路径
        //判断文章小程序二维码是否存在        
        if(is_file($qrcodePath)) {

            $result['qrcodeUrl']=$qrcodeUrl;
            $result['qrcodePath']=$qrcodePath;            
            return $result;            
        }
        $color = array(
            "r" => "0",  
            "g" => "0", 
            "b" => "0", 
        );
        $data = array(
            
            'path' => $path, //前端传过来的页面path
            'width' => 430, //设置二维码尺寸
            'auto_color' => false,
            'line_color' => $color,
        );
        $qrcoderesult= RAM()->wxapi->get_qrcode($data);       
        if(isset($qrcoderesult['errcode']))
        {
            $errcode=(int)$qrcoderesult['errcode'];            
            if($errcode==0)
            {
                $qrcode= $qrcoderesult['buffer'];                
                if (file_put_contents($qrcodePath,$qrcode) !== false) { 
                    $result['qrcodeUrl']=$qrcodeUrl;
                    $result['qrcodePath']=$qrcodePath;
                    $result['errcode']="0";
                }
                else
                {
                    $result['errcode'] = "3";
                    $result['errmsg'] = "无法保存二维码图片文件";
                }             
                
            
            }
            else
            {
    
                $result['errcode']="1";
                $result['errmsg'] = "生成二维码错误:".getErrorMessage($errcode);
    
            }
        }
        else
        {
            $result['errcode']="2";
            $result['errmsg']="调用cURL出错";
                
        }
        return $result;



    }

    /**
 * 获取错误信息
 *
 * @param int $errcode 错误码
 * @return string 错误信息
 */
function getErrorMessage($errcode)
{
    $errorMessages = array(
        40001 => 'invalid credential access_token is invalid or not latest',
        40159 => 'invalid length for path or the data is not json string',
        45029 => 'qrcode count out of limit',
        85096 => 'not allow include scancode_time field',
        40097 => 'invalid args',
    );

    if (isset($errorMessages[$errcode])) {
        return $errorMessages[$errcode];
    }

    return "生成二维码错误，错误码：" . $errcode;
}

function get_history_post_list($post_year, $post_month, $post_day){
	global $wpdb;
	$limit = 10;
	$order = "latest";
	if($order == "latest"){ $order = "DESC";} else { $order = '';}
	// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
    // phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
	$histtory_post = $wpdb->get_results($wpdb->prepare("select ID, year(post_date_gmt) as post_year,date(post_date_gmt) as post_date, post_title, comment_count FROM 
	$wpdb->posts WHERE post_password = '' AND post_type = 'post' AND post_status = 'publish'
	AND year(post_date_gmt)!=%s AND month(post_date_gmt)=%s AND day(post_date_gmt)=%s
	order by post_date_gmt  limit %d,%d",$post_year,$post_month,$post_day,$order,$limit));
    // phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
    // phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
	return $histtory_post;
}

function getRecommendWechatShopGoods($post_id)
{
    
    $wechatshopGoods = empty(get_post_meta($post_id, '_wechatshopGoods', true)) ? '' : get_post_meta($post_id, '_wechatshopGoods', true);

    $recommendGoods = array();
    if (!empty($wechatshopGoods)) {
        $cnt = count($wechatshopGoods['appid']);
        for ($i = 0; $i < $cnt; $i++) {
            $goods = array();
            $goods["type"] = "miniappGoods";
            $goods['redirecttype'] = "wechatshop";
            $goods["title"] = $wechatshopGoods['title'][$i];
            $goods["storeappid"] = $wechatshopGoods['appid'][$i];
            $goods["productid"] = $wechatshopGoods['productid'][$i];
            $goods["productpromotionlink"] = $wechatshopGoods['productpromotionlink'][$i];
            $recommendGoods[] = $goods;
        }
    }
    return $recommendGoods;
}

function minapper_verify()
{
    $minapper_weixin_user=get_option( 'minapper_weixin_user');
    if(!empty($minapper_weixin_user))
    {
        $verify_result = get_transient('minapper-verify-result'); 
        if(empty($verify_result))
        {
            $openid= $minapper_weixin_user['openid'];        
            $args = array(
                'body' => json_encode(array('openid' => $openid)),
                'headers' => array('Content-Type' => 'application/json'),
            );
            $response = wp_remote_post('https://plus.minapper.com/wp-json/minapper/v1/wechat/verify', $args);
            if (is_array($response) && !is_wp_error($response) && wp_remote_retrieve_response_code($response) == 200) {
                $result = json_decode(wp_remote_retrieve_body($response), true);
                if($result['success'])
                {
                    if($result['issubscribe']=='1')
                    {
                        set_transient('minapper-verify-result', $result, 24*7 * HOUR_IN_SECONDS);
                        return true;
                    }
                    else
                    {
                        delete_option('minapper_weixin_user');                        
                        return false;
                    }
                  
                }
                
            }
            else
            {
                delete_user_meta($user_id, 'minapper_weixin_user');
                return false;
            }
        }
        else
        {
            return true;
        }
    }
    

}

