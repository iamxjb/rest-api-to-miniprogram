<?php

//禁止直接访问
if ( ! defined( 'ABSPATH' ) ) exit;

class RAW_Weixin_API {
	
	//获取Access Token
	public function get_access_token($appid,$secret) {
		
		if( ($access_token = get_option('raw-access_token')) !== false && ! empty( $access_token ) && time() < $access_token['expire_time']) {
			return $access_token['access_token'];
		}		
		
		$api_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='. $appid .'&secret='. $secret;
		$response = wp_remote_get( $api_url );		
		if( ! is_wp_error( $response ) && is_array( $response ) && isset( $response['body'] ) ) {			
			$result = json_decode( $response['body'], true );
			if( ! isset( $result['errcode'] ) || $result['errcode'] == 0 ) {
				
				$access_token = array(
					'access_token' => $result['access_token'],
					'expire_time' => time() + intval( $result['expires_in'] )
				);
				update_option( 'raw-access_token', $access_token );				
				return $access_token['access_token'];
			}
		}
		
		return false;
	}
	
	// 获取微信公众平台API地址
	public function API( $key) {
		
		
		$api_urls = array(
			'all_template' => 'https://api.weixin.qq.com/cgi-bin/wxopen/template/library/list',
			'my_template' => 'https://api.weixin.qq.com/cgi-bin/wxopen/template/list',
			'get_template_keywords' => 'https://api.weixin.qq.com/cgi-bin/wxopen/template/library/get',
			'add_template' => 'https://api.weixin.qq.com/cgi-bin/wxopen/template/add',
			'delete_template' => 'https://api.weixin.qq.com/cgi-bin/wxopen/template/del',
			'send_template' => 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send'
		);
		
		return $api_urls[$key];
	}
	
	// 发起API请求
	private function request( $url, $method, $body ) {
		
		$response = wp_remote_request( $url, array(
			'method' => $method,
			'body' => json_encode( $body )
		) );
		
		return ! is_wp_error( $response ) ? json_decode( $response['body'], true ) : false;
	}
	
	// 获取所有模板列表或帐号下模板列表
	public function get_templates( $type = 'my', $args = array() ) {
		
		$key = '';
		switch( $type ) {
			case 'all':
				$key = 'all_template';
				break;
			case 'my':
				$key = 'my_template';
				break;
			default:
				return false;
		}
		
		$api_url = $this->API($key);
		$body = RAW_Util::param_atts( array(
			'offset' => 0,
			'count' => 10
		), $args );
		
		$result = $this->request( $api_url, 'POST', $body );
		return $result ? $result['list'] : false;
	}
	
	// 获取模板库某个模板标题下关键词库
	public function get_template_keywords( $id ) {
		
		$api_url = $this->API('get_template_keywords');
		
		$result = $this->request( $api_url, 'POST', array( 'id' => $id ) );
		return $result ? $result['keyword_list'] : false;
	}
	
	// 组合模板并添加至帐号下的个人模板库
	public function add_template( $args ) {
		
		$api_url = $this->API('add_template');
		
		$result = $this->request( $api_url, 'POST', $args );
		return $result ? $result['template_id'] : false;
	}
	
	// 删除帐号下的某个模板
	public function delete_template( $template_id ) {
		
		$api_url = $this->API('delete_template');
		
		$result = $this->request( $api_url, 'POST', array( 'template_id' => $template_id ) );
		return $result ? true : false;
	}
	
	// 发送模板消息
	public function send_template($appid,$secret,$touser, $template_id, $page, $form_id, $data, $emphasis_keyword ) {
		$access_token = $this->get_access_token($appid,$secret);
		$access_token= $access_token?'?access_token=' . $access_token:'';
		$api_url = $this->API('send_template');
		$error="0";
		$message="ok";
		if(!empty($access_token))
		{
			$api_url=$api_url.$access_token;
			$result = $this->request( $api_url, 'POST', array(
			'touser' => $touser,
			'template_id' => $template_id,
			'page' => $page,
			'form_id' => $form_id,
			'data' => $data,
			'emphasis_keyword' => $emphasis_keyword,
			) );
			$error =$result['errcode'];
			if($error=='41029')
	        {

	        	$message ='formid已被使用';
	        }
	        
			if($error=='40037')
	        {
	        	$message ='templateid不正确';	        	
	        }
	        if($error=='41028')
	        {
	        	$message ='formid不正确，或者过期';	
	        	 
	        }
	        if($error=='41030')
	        {
	        	$message ='page不正确';	
	        	
	        }
	        if($error=='45009')
	        {
	        	$message ='接口调用超过限额';	
	        	 
	        }

	        if($error=='0')
	        {
	        	$message ='发送成功';

	        }
	        
		}
		else
		{
			$error ='01';
			$message ='获取access_token失败';
	        	 
			
		}

		$result = array(
					'error' => $error,
					'message' =>$message 
				);

		return  $result;	
		
		
	}
}

