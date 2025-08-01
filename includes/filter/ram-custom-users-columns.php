<?php 
//禁止直接访问
if ( ! defined( 'ABSPATH' ) ) exit;
function ram_users_columns( $columns ){
    $columns[ 'avatar' ] = __( '头像','rest-api-to-miniprogram' ); 
	$columns[ 'registered' ] = __( '注册时间','rest-api-to-miniprogram' ); 
    return $columns;
}
function  output_ram_users_columns( $var, $columnName, $userId ){
    switch( $columnName ) {
		case "avatar":
			return getAvatar($userId);
			break;	
		case "registered":
			return getRegisteredDate($userId);
			break;	
	}

}

function ram_users_sortable_columns($sortable_columns){
    $sortable_columns['registered'] = 'registered';
    return $sortable_columns;
}
//最后根据浏览器的 url，重新设置 wordpress 的查询函数
function ram_users_search_order($obj){
	// phpcs:disable WordPress.Security.NonceVerification.Recommended
	$order=isset($_REQUEST['orderby']) ? sanitize_text_field(wp_unslash($_REQUEST['orderby'])) : '';
    // // phpcs:enable WordPress.Security.NonceVerification.Recommended
	if($order=='registered' ){
        if(!in_array($order,array('asc','desc')) ){
            $order = 'desc';
        }
        $obj->query_orderby = "ORDER BY user_registered ".$order."";
    }
}
function getRegisteredDate($userId)
{
  $user=get_user_by('ID',$userId);
  $resutlt =$user->user_registered;
  return $resutlt;
}

function  getAvatar($userId)
{
	$avatar= get_user_meta( $userId, 'avatar', true );
	if(empty($avatar))
	{		
		$avatarImg ='<img  src="'.plugins_url().'/'.REST_API_TO_MINIPROGRAM_PLUGIN_NAME.'/includes/images/gravatar.png"  width="50px" heigth="50px"/>';
	}
	else{
		$avatarImg ='<img  src="'.$avatar.'"  width="50px" heigth="50px"/>';
		
	}

	return $avatarImg;

}