<?php 
//禁止直接访问
if ( ! defined( 'ABSPATH' ) ) exit;


function ram_posts_columns( $columns ) {
    $columns['id'] = __('id');
    return $columns;
}

function output_ram_posts_custom_columns( $column,$post_id)
{
    if($column=='id') echo $post_id; 

}


function ram_pages_columns( $columns ) {
    $columns['id'] = __('id');
    return $columns;
}

function output_ram_pages_custom_columns( $column,$post_id)
{
    if($column=='id') echo $post_id; 

}
