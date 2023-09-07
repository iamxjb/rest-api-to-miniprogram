<?php 
//禁止直接访问
if ( ! defined( 'ABSPATH' ) ) exit;


function ram_posts_columns( $columns ) {
    $columns['id'] = __('id');
    $columns['qrcode'] = __('小程序码');
    return $columns;
}

function output_ram_posts_custom_columns( $column,$post_id)
{
    if($column=='id') echo $post_id; 
    if($column=='qrcode')
    {
        $qrcode=creat_minapper_qrcode($post_id);
        $qrcode=$qrcode['qrcodeUrl'];
        $qrcode = "<p><img  width='80' src='" .$qrcode. "' ></p>";
        echo $qrcode;
    }

}


function ram_pages_columns( $columns ) {
    $columns['id'] = __('id');
    return $columns;
}

function output_ram_pages_custom_columns( $column,$post_id)
{
    if($column=='id') echo $post_id; 

}
