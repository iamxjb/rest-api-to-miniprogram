<?php

function custom_comment_fields( $data, $comment, $request) {
    global $wpdb;
    $_data = $data->data; 
    $user_id=(int)$comment->user_id;
    if($user_id==0)
    {
        $_data['author_url']=plugins_url() . '/' . REST_API_TO_MINIPROGRAM_PLUGIN_NAME . '/includes/images/gravatar.png';
    }
    else
    {
        $_data['author_url']= get_user_meta($user_id, 'avatar', true );
    }
   
    $data->data = $_data;
    return $data; 
}


