<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once( REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/wxpay/WxPay.Api.php' );
require_once( REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/wxpay/WxPay.JsApiPay.php' );
require_once( REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/wxpay/WxPay.Notify.php' );


class RAW_REST_Payment_Controller  extends WP_REST_Controller{

    public function __construct() {
        $this->namespace     = 'watch-life-net/v1';
        $this->resource_name = 'payment';
    }


     // Register our routes.
    public function register_routes() {

        register_rest_route( $this->namespace, '/' . $this->resource_name, array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'POST',
                'callback'  => array( $this, 'post_payment' ),
               'permission_callback' => array( $this, 'post_payment_permissions_check' ),
                'args'               => array(            
                    'openid' => array(
                        'required' => true
                    ),
                    'totalfee' => array(
                        'required' => true
                    )
                   
                )
            ),
            // Register our schema callback.
            'schema' => array( $this, 'post_public_item_schema' ),
        ) );

        register_rest_route( $this->namespace, '/' . $this->resource_name . '/notify', array(
            array(
                'methods'             => 'GET',
                'callback'            => array( $this, 'notify' ),
                'permission_callback' => array( $this, 'get_notify_permissions_check' ),
                'args'                => $this->get_collection_params()
            )
        ) );

    }

    public function  post_payment($request){
        
        date_default_timezone_set('Asia/Shanghai');
        $openId=isset($request['openid'])?$request['openid']:'';        
        $totalFee=isset($request['totalfee'])? $request['totalfee']:1;

        if(!is_numeric($totalFee))
        {

            return new WP_Error( 'error', "totalfee参数错误", array( 'status' => 400 ) );
        }
        
        
        $appId=WxPayConfig::get_appid();
        $mchId=WxPayConfig::get_mchid();
        $key=WxPayConfig::get_key();
        $body=WxPayConfig::get_body();

        if(empty($appId) || empty($mchId) || empty($key) || empty($body)) {
            
            return new WP_Error( 'error', "请填写AppID、商户号、商户支付密钥和支付描述", array( 'status' => 400 ) );
        }

        $tools = new JsApiPay();

        //②、统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody($body);
        $orderId =WxPayConfig::get_mchid().date("YmdHis");
        $input->SetOut_trade_no($orderId);
        $input->SetTotal_fee(strval($totalFee*100));
        //$input->SetTotal_fee(strval($totalFee));
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetNotify_url(get_rest_url( null, $this->namespace . '/' . $this->resource_name . '/notify' ) );
        $input->SetTrade_type( 'JSAPI' );
        $input->SetOpenid($openId);

         $order = WxPayApi::unifiedOrder($input);

        $jsApiParameters = $tools->GetJsApiParameters($order);

        $jsApiParameters['success'] = 'success';
        return  $jsApiParameters;

    }

    // 支付通知
    public function notify( $request ) {        
        
        $notify = new RAW_PayNotifyCallBack();
        $notify->Handle( false );
    }
    public function post_payment_permissions_check($request) {
        $openId =isset($request['openid'])? $request['openid']:"";       
        if(empty($openId) || !username_exists($openId))
        {
            return new WP_Error( 'user_parameter_error', "用户参数错误", array( 'status' => 400 ) );
        }
        $totalFee=isset($request['totalfee'])? (int)$request['totalfee']:1;
        if(!is_int($totalFee))
        {
            return new WP_Error( 'error', 'totalfee参数错误', array( 'status' => 400 ) );
        }
        return true;
    }

    /**
     * Check whether a given request has permission to read order notes.
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return WP_Error|boolean
     */
    public function get_notify_permissions_check( $request ) {
        return true;
    }


}  

class RAW_PayNotifyCallBack extends WxPayNotify {
    
    // 重写回调处理函数
    public function NotifyProcess( $data, &$msg ) {
        
        if( ! array_key_exists( 'transaction_id' , $data ) ) {
            $msg = '输入参数不正确';
            return false;
        }
        if(!RAW_Util::check_notify_sign($data,get_option('raw_paykey'))){
            $msg = 'key错误';
            return false;
        }
        
        
        return true;
    }
}