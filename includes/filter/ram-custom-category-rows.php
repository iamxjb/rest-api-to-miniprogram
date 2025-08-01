<?php 
//禁止直接访问
if ( ! defined( 'ABSPATH' ) ) exit;
function ram_custom_taxonomy_columns( $columns )
{
    $columns['id'] = __('id','rest-api-to-miniprogram');


    return $columns;
}

function ram_custom_taxonomy_columns_content( $content, $column_name, $term_id )
{

    if($column_name=="id"){
        echo esc_html($term_id);
    }
    
}