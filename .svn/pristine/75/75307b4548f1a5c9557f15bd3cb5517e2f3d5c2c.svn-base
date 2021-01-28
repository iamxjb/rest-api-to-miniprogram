<?php
//解析腾讯视频，只支持一个腾讯视频
function custocm_content_filter($content) {

    $_content=$content;
    if(is_single())
    {
        $vcontent =get_post_qq_video($content);
        if(!empty($vcontent))
        {
            $_content=$vcontent;
        }
    }

    return $_content;

}



