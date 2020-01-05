<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class RAM_REST_Options_Controller  extends WP_REST_Controller{

    public function __construct() {
        
        $this->namespace     = 'watch-life-net/v1';
        $this->resource_name = 'options';
    }

    // Register our routes.
    public function register_routes() {
        register_rest_route( $this->namespace, '/' . $this->resource_name.'/enableComment', array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'getEnableComment' ),
                'permission_callback' => array( $this, 'get_item_permissions_check' )
                 
            ),
            // Register our schema callback.
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );

        register_rest_route( $this->namespace, '/' . $this->resource_name, array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'get_item' ),
                'permission_callback' => array( $this, 'get_item_permissions_check' )
                 
            ),
            // Register our schema callback.
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );

    }

    public function get_item($request)
    {
        $wf_enable_comment_option  =empty(get_option('wf_enable_comment_option'))?"0":get_option('wf_enable_comment_option');
        $interstitialAdId  =empty(get_option('wf_interstitial_ad_id'))?"":get_option('wf_interstitial_ad_id');
        $wf_enterprise_minapp  =empty(get_option('wf_enterprise_minapp'))?"0":get_option('wf_enterprise_minapp');
       
        $result["wf_enable_comment_option"]=$wf_enable_comment_option;
        $result["interstitialAdId"]=$interstitialAdId;
        $result["wf_enterprise_minapp"]=$wf_enterprise_minapp;
        $response = rest_ensure_response( $result);
        return $response;
    }

    public function getEnableComment($request)
    {
        $wf_enable_comment_option  =get_option('wf_enable_comment_option');
        $interstitial_ad_id  =get_option('wf_interstitial_ad_id');
        if(empty($wf_enable_comment_option ))
        {
            $result["code"]="success";
            $result["message"]= "获取是否开启评论成功";
            $result["status"]="200";
            $result["enableComment"]="0";
        }
        else
        {
            $result["code"]="success";
            $result["message"]= "获取是否开启评论成功";
            $result["status"]="200";
            $result["enableComment"]="1";
            
        }
        $response = rest_ensure_response( $result);
        return $response;
    }


    public function get_item_permissions_check($request ) {      
        
        return true;
    }



}