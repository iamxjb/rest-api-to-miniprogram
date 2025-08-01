<?php 
//禁止直接访问
if ( ! defined( 'ABSPATH' ) ) exit;


function ram_posts_columns( $columns ) {
    $columns['id'] = __('id','rest-api-to-miniprogram');
    $columns['excitation'] = __('启用激励视频','rest-api-to-miniprogram');
    $columns['qrcode'] = __('小程序码','rest-api-to-miniprogram');
    return $columns;
}

function output_ram_posts_custom_columns( $column,$post_id)
{
    if($column=='id') echo esc_html($post_id); 
    if($column=='excitation')
    {
        $excitation=empty(get_post_meta($post_id,'_excitation',true))?0:(int)get_post_meta($post_id,'_excitation',true);

        if($excitation==1)
        {
            echo '是';
        }
        else
        {
            echo '否';
        }
    
    } 
    if($column=='qrcode')
    {
        $qrcode_file = sprintf('%s/qrcode-%d.png', REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'qrcode/', $post_id);

        if (file_exists($qrcode_file)) {
            // 如果小程序码图片存在,则显示该图片
            $qrcode_url = plugins_url() . '/' . REST_API_TO_MINIPROGRAM_PLUGIN_NAME . '/qrcode/qrcode-' . $post_id . '.png';
            echo "<p><img width='80' src='".esc_html($qrcode_url)."' alt='QR Code'/></p>";
        } else {
            // 否则显示占位符或提示信息
            echo '<span class="qrcode-placeholder">暂未生成</span>';
        }
    }

}


function ram_pages_columns( $columns ) {
    $columns['id'] = __('id','rest-api-to-miniprogram');

    return $columns;
}

function output_ram_pages_custom_columns( $column,$post_id)
{
    if($column=='id') echo esc_html($post_id); 

}

function ram_posts_custom_bulk_actions( $bulk_array ) {
	$bulk_array['creat_post_qrcode'] = '生成小程序码';	
    $bulk_array['enable_post_excitation'] = '启用激励视频';	
    $bulk_array['cancel_post_excitation'] = '取消激励视频';	
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

    if ( $doaction == 'enable_post_excitation' ){
        foreach ( $object_ids as $post_id ) {			
            update_post_meta($post_id,'_excitation','1'); 
        }
        $redirect = add_query_arg( 'ram_post_custom_bulk_actions', count( $object_ids ), $redirect );
    }

    if ( $doaction == 'cancel_post_excitation' ){
        foreach ( $object_ids as $post_id ) {			
            update_post_meta($post_id,'_excitation','0'); 
        }
        $redirect = add_query_arg( 'ram_post_custom_bulk_actions', count( $object_ids ), $redirect );
    }
    

    return $redirect;

}

add_action( 'admin_notices', 'ram_post_custom_bulk_actions_notices' );

function ram_post_custom_bulk_actions_notices() {
	// 改为草稿
    $ram_post_custom_bulk_actions=isset($_REQUEST['ram_post_custom_bulk_actions']) ? sanitize_text_field(wp_unslash($_REQUEST['ram_post_custom_bulk_actions'])) : '';
	if (!empty($ram_post_custom_bulk_actions) && wp_verify_nonce( $ram_post_custom_bulk_actions, 'ram_post_custom_bulk_actions' ) ) {
		echo '<div id="message" class="updated notice is-dismissible">
			<p>小程序码已生成</p>
		</div>';
	}
}
 

