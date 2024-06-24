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
        $qrcode_file = sprintf('%s/qrcode-%d.png', REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'qrcode/', $post_id);

        if (file_exists($qrcode_file)) {
            // 如果小程序码图片存在,则显示该图片
            $qrcode_url = plugins_url() . '/' . REST_API_TO_MINIPROGRAM_PLUGIN_NAME . '/qrcode/qrcode-' . $post_id . '.png';
            echo "<p><img width='80' src='{$qrcode_url}' alt='QR Code'/></p>";
        } else {
            // 否则显示占位符或提示信息
            echo '<span class="qrcode-placeholder">暂未生成</span>';
        }
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

function ram_posts_custom_bulk_actions( $bulk_array ) {
	$bulk_array['creat_post_qrcode'] = '生成小程序码';	
	return $bulk_array;
}

function ram_posts_custom_bulk_actions_handler($redirect, $doaction, $object_ids)
{
    if ( $doaction == 'creat_post_qrcode' ){
        foreach ( $object_ids as $post_id ) {
			creat_minapper_qrcode($post_id);
        }
        $redirect = add_query_arg( 'ram_post_custom_bulk_actions', count( $object_ids ), $redirect );
    }
    

    return $redirect;

}

add_action( 'admin_notices', 'ram_post_custom_bulk_actions_notices' );

function ram_post_custom_bulk_actions_notices() {
	// 改为草稿
	if ( ! empty( $_REQUEST['ram_post_custom_bulk_actions'] ) ) {
		echo '<div id="message" class="updated notice is-dismissible">
			<p>小程序码已生成</p>
		</div>';
	}
}
 

