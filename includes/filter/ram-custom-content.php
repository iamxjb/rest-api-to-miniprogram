<?php
//解析腾讯视频，只支持一个腾讯视频
function custocm_content_filter($content)
{

    $_content = $content;
    if (is_single()) {
        $vcontent = get_post_qq_video($content);
        if (!empty($vcontent)) {
            $_content = $vcontent;
        }
    }

    $postId = get_the_ID();
    $excitationAd = empty(get_post_meta($postId, '_excitation', true)) ? "0" : get_post_meta($postId, '_excitation', true);
    $post = get_post($postId);

    
    $minapper_qrcode_url = empty(get_option('wf_minapper_qrcode_url')) ? '' : get_option('wf_minapper_qrcode_url');
    $display_qrcode = empty(get_option('wf_detail_bottom_display_qrcode')) ? '0' : get_option('wf_detail_bottom_display_qrcode');
    $excerpt = empty($post->post_excerpt) ? "" : $post->post_excerpt;
    if (strlen($excerpt) < 1 &&  strlen($_content) > 0) {
        $excerpt = ram_rewrite_content($_content, 500);
    }
    $message='';    
    if (is_single()) {
        $message = '微信扫描下方的二维码阅读本文<br/><br/>';
        if ($excitationAd == '1') { 
            $message = '微信扫描下方的二维码阅读全文<br/><br/>';       
            $_content = $excerpt;        
        }
      
        if($display_qrcode == '1')
        {  
            
            $qrcode=creat_minapper_qrcode($postId);
            if($qrcode['errcode'] == '0' && !empty($qrcode['qrcodeUrl']))
            {
                 $qrcode=$qrcode['qrcodeUrl'];
            $qrcode = "<p><img  width='150' src='" .$qrcode. "' ></p>";             
            $_content .= "<div style='width:100%,margin-top:20px;text-align:center;'><br/><br/>" . $message . $qrcode . "</div>";
            }
           
        }
        else
        {
            if ($excitationAd == '1') {  
                $message = '微信扫描下方的二维码阅读本文<br/><br/>';
                $qrcode = "<p><img  width='150' src='" . $minapper_qrcode_url . "' ></p>";
                $_content .= "<div style='width:100%,margin-top:20px;text-align:center;'><br/><br/>" . $message . $qrcode . "</div>";
              
            }
           
        }
       
        
    }
    return $_content;
}
