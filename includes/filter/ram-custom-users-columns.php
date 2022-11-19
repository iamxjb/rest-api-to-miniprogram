<?php 
//禁止直接访问
if ( ! defined( 'ABSPATH' ) ) exit;
function ram_users_columns( $columns ){
    $columns[ 'avatar' ] = __( '头像' ); 
	$columns[ 'registered' ] = '注册时间'; 
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
    if(isset($_REQUEST['orderby']) && $_REQUEST['orderby']=='registered' ){
        if(!in_array($_REQUEST['order'],array('asc','desc')) ){
            $_REQUEST['order'] = 'desc';
        }
        $obj->query_orderby = "ORDER BY user_registered ".$_REQUEST['order']."";
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