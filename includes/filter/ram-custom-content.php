<?php
//解析腾讯视频，只支持一个腾讯视频
function custocm_content_filter($content) {

    
    // $siteurl = get_option('siteurl');
 //     $upload_dir = wp_upload_dir();
 //     $content = str_replace( 'http:'.strstr($siteurl, '//'), 'https:'.strstr($siteurl, '//'), $content);
 //     $content = str_replace( 'http:'.strstr($upload_dir['baseurl'], '//'), 'https:'.strstr($upload_dir['baseurl'], '//'), $content);
    preg_match('/https\:\/\/v.qq.com\/x\/(\S*)\/(\S*)\.html/',$content,$matches);
    if($matches)
    {
        $vids=$matches[2];

        //$url='http://vv.video.qq.com/getinfo?vid='.$vids.'&defaultfmt=auto&otype=json&platform=1&defn=fhd&charge=0';
        //  defaultfmt： 1080P-fhd，超清-shd，高清-hd，标清-sd
        $url='http://vv.video.qq.com/getinfo?vid='.$vids.'&defaultfmt=auto&otype=json&platform=11001&defn=fhd&charge=0';
        $res = file_get_contents($url);
        if($res)
        {
            $str = substr($res,13,-1);
            $newStr =json_decode($str,true);        
            //$videoUrl= $newStr['vl']['vi'][0]['ul']['ui'][2]['url'].$newStr['vl']['vi'][0]['fn'].'?vkey='.$newStr['vl']['vi'][0]['fvkey']; 
            $videoUrl= $newStr['vl']['vi'][0]['ul']['ui'][0]['url'].$newStr['vl']['vi'][0]['fn'].'?vkey='.$newStr['vl']['vi'][0]['fvkey']; 
            $contents = preg_replace('~<video (.*?)></video>~s','<video src="'.$videoUrl.'" controls="controls" width="100%"></video>',$content);
            return $contents;

        }
        else
        {
            return $content;
        }
        
    }
    else
    {
        return $content;
    }
}



