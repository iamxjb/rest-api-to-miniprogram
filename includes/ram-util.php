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

    if(empty($content_first_image))
    {
        $content_first_image='';
    }

    if(empty($post_frist_image))
    {
        $post_frist_image='';
    }

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
        $image=wp_get_attachment_image_src($thumbnailId, 'thumbnail');
        $post_thumbnail_image=$image[0];
        $post_thumbnail_image_150=$image[0];
        //特色中等图
        $image=wp_get_attachment_image_src($thumbnailId, 'medium');
        $post_medium_image=$image[0];
        $post_medium_image_300=$image[0];
        //特色大图
        $image=wp_get_attachment_image_src($thumbnailId, 'large');
        $post_large_image=$image[0];
        $post_thumbnail_image_624=$image[0];
        //特色原图
        $image=wp_get_attachment_image_src($thumbnailId, 'full');
        $post_full_image=$image[0];

    }

    if(!empty($content_first_image) && empty($post_thumbnail_image))
     {
        $post_thumbnail_image=$content_first_image;
        $post_thumbnail_image_150=$content_first_image;
     }

     if(!empty($content_first_image) && empty($post_medium_image))
     {
        $post_medium_image=$content_first_image;
        $post_medium_image_300=$content_first_image;
        
     }

     if(!empty($content_first_image) && empty($post_large_image))
     {
        $post_large_image=$content_first_image;
        $post_thumbnail_image_624=$content_first_image;
     }

     if(!empty($content_first_image) && empty($post_full_image))
     {
        $post_full_image=$content_first_image;
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
    if($code == "200"){
        return $content;
    }else{
        return "error";
    }
}

//发起https请求
function https_request($url)
{
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
    return $data;
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


function time_tran($the_time){
    $now_time = date("Y-m-d H:i:s",time()+8*60*60); 
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
                         return date("Y-m-d",$show_time); 
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
function decrypt_data( $appid, $sessionKey, $encryptedData, $iv, &$data ) {
    
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

function get_client_ip()
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
            foreach (explode(',', $_SERVER[$key]) as $ip) {
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

function filterEmoji($str)
{
  $str = preg_replace_callback(
    '/./u',
    function (array $match) {
      return strlen($match[0]) >= 4 ? '' : $match[0];
    },
    $str);

  return $str;
}

function  getUserLevel($userId)
{
    global $wpdb;
    $sql =$wpdb->prepare("SELECT  t.meta_value
            FROM
                ".$wpdb->usermeta." t
            WHERE
                t.meta_key = '". $wpdb->prefix."user_level' 
            AND t.user_id =%d",$userId);

    $level =$wpdb->get_var($sql); 
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

function get_post_qq_video($content)
{
    $vcontent ='';
    preg_match('/https\:\/\/v.qq.com\/x\/(\S*)\/(\S*)\.html/',$content,$matches);
    if($matches)
    {
    	$vids=$matches[2];
	    //$url='http://vv.video.qq.com/getinfo?vid='.$vids.'&defaultfmt=auto&otype=json&platform=1&defn=fhd&charge=0';
	    //  defaultfmt： 1080P-fhd，超清-shd，高清-hd，标清-sd
	    $url='http://vv.video.qq.com/getinfo?vid='.$vids.'&defaultfmt=auto&otype=json&platform=11001&defn=fhd&charge=0';
	    //$res = file_get_contents($url);
        $res = https_request($url);
	    if($res)
	    {
	    	$str = substr($res,13,-1);
		    $newStr =json_decode($str,true);	    
		    //$videoUrl= $newStr['vl']['vi'][0]['ul']['ui'][2]['url'].$newStr['vl']['vi'][0]['fn'].'?vkey='.$newStr['vl']['vi'][0]['fvkey']; 
		    $videoUrl= $newStr['vl']['vi'][0]['ul']['ui'][0]['url'].$newStr['vl']['vi'][0]['fn'].'?vkey='.$newStr['vl']['vi'][0]['fvkey']; 
		    $vcontent = preg_replace('~<video (.*?)></video>~s','<video src="'.$videoUrl.'" controls="controls" width="100%"></video>',$content);
        
        }	    
	    
    }

    return $vcontent;
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
        $img='';
        foreach($ids as $id)
        {
            $image=wp_get_attachment_image_src((int)$id,'full');

            $img .='<img width="'.$image[1].'" height="'.$image[2].'" src="'.$image[0].'" />';
           

        }
        $vcontent = preg_replace('~\[gallery (.*?)\]~s',$img,$content);
        

    }

    return $vcontent;
        
}

function  getPosts($ids)
    {
        global $wpdb;
        $sql="SELECT *  from ".$wpdb->posts." where id in(".$ids.") ORDER BY find_in_set(id,'".$ids."')";
        $_posts = $wpdb->get_results($sql);
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
    
                $comment_total = $wpdb->get_var("SELECT COUNT(1) FROM ".$wpdb->comments." where  comment_approved = '1' and comment_post_ID=".$post_id);
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