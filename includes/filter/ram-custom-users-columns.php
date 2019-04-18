<?php 
//禁止直接访问
if ( ! defined( 'ABSPATH' ) ) exit;
function users_columns( $columns ){
    $columns[ 'avatar' ] = __( '头像' );    
    return $columns;
}
function  output_users_columns( $var, $columnName, $userId ){
    switch( $columnName ) {
		case "avatar" :
			return getAvatar($userId);
			break;
	}

}

function  getAvatar($userId)
{
	$avatar= get_user_meta( $userId, 'avatar', true );
	if(empty($avatar))
	{		
		$avatarImg ='<img  src="'.plugins_url().'/'.REST_API_TO_MINIPROGRAM_PLUGIN_NAME.'/includes/images/gravatar.png"  width="20px" heigth="20px"/>';
	}
	else{
		$avatarImg ='<img  src="'.$avatar.'"  width="20px" heigth="20px"/>';
		
	}

	return $avatarImg;

}