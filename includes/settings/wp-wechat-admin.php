<?php 

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
function minapper_admin_menu()
{
   
    $swipe_count=50;
    $selected_nav_count=50; 
    $selected_shops_count=10;
    $config_submenu = array(
        'type'              => 'menu',                          // Required, menu or metabox
        'id'                => 'minapper_expand_settings_page',              // Required, meta box id, unique per page, to save: get_option( id )
        'parent'            => 'weixinapp_slug',                   // Parent page of plugin menu (default Settings [options-general.php])
        'submenu'           => true,                            // Required for submenu
        'title'             => '扩展设置',                       // The title of the options page and the name in admin menu
        'capability'        => 'manage_options',                // The capability needed to view the page
        'plugin_basename'   =>  plugin_basename(plugin_dir_path(__DIR__) . REST_API_TO_MINIPROGRAM_PLUGIN_NAME. '.php'),
        'tabbed'            => true,
        'multilang'         => false,                        // To turn of multilang, default on.
        'icon'              => plugins_url(REST_API_TO_MINIPROGRAM_PLUGIN_NAME.'/images/icon16.png')

    );   

    $fields[] = array(
        'name'   => 'swipe_options',
        'title'  => '首页轮播图',
        'icon'   => 'fa fa-home',
        'fields' => array(

            array(
                'type'    => 'group',
                'id'      => 'swipe_nav',
                'title'   => '跳转设置',
                'options' => array(
                    'repeater'          => true,
                    'accordion'         => true,                  
                    'button_title'      => '添加',
                    'group_title'       => '自定义跳转',
                    'limit'             => $swipe_count,
                    'sortable'          => true,
                    'closed'            => true,
                    
                ),             

                'fields'  => array(    
                    
                    array(
                        'id'             => 'type',
                        'type'           => 'select',
                        'title'          => '跳转类型',
                        'options'        => array(
                            'apppage'          => '跳转到小程序内页面',
                            'miniapp'     => '跳转其他小程序',
                            'webpage'   => '跳转到网页'
                        ),                      
                        'default'     => 'apppage',                             // optional 
                        'class'       => 'chosen',                          // optional 
                       
                    ),  
                    array(
                        'id'             => 'jumptype',
                        'type'           => 'select',
                        'title'          => '跳转方式',
                        'after'       => '跳转其他小程序时需选择',
                        'options'        => array(
                            'redirect'          => '切换跳转',
                            'embedded'     => '半屏跳转'                            
                        ),                      
                        'default'     => 'redirect',                             // optional 
                        'class'       => 'chosen',  
                    ),              
                    array(
                        'id'      => 'appid',
                        'type'    => 'text',
                        'title'   => 'appid',
                        'after'       => '跳转其他小程序时需填写',   // optional   
                        'attributes' => array(
                            'data-title' => 'title',
                            'placeholder' => '请输入小程序appid'
                        )
                    ),                    
                    array(
                        'id'    => 'image',
                        'type'  => 'image',
                        'title' => '图片',
                    ),
                     array(
                        'id'      => 'path',
                        'type'    => 'text',
                        'title'   => '小程序路径',
                        'after'       => '跳转小程序时需填写。
                        <br/>跳转本小程序的页面路径请以<font color=red>"/pages"</font>开头。
                        <br/>跳转其他小程序的页面路径一般以<font color=red>"pages"</font>开头。
                        <br/>跳转直播间<font color=red>"plugin-private://wx2b03c6e691cd7370/pages/live-player-plugin?room_id=11"</font>开头。room_id换成直播间的房间号',   // optional   
                        'attributes' => array(
                            'data-title' => 'title',
                            'placeholder' => '请输入小程序页面路径'
                        )
                        ),
                   
                    array(
                        'id'      => 'url',
                        'type'    => 'text',
                        'title'   => 'url地址',
                        'after'       => '跳转到网页需填写。链接包括：小程序业务域名链接，公众号文章链接',  
                        'attributes' => array(
                            'data-title' => 'title',
                            'placeholder' => '请输入链接'
                        )
                    ),
                    array(
                        'id'    => 'unassociated',
                        'type'  => 'checkbox',
                        'title' => '网页链接是没有关联小程序的公众号文章',
                        'after'       => '跳转<font color=red>没有关联</font>小程序的公众号文章时选中'
                        //'label' => '是否启用?'
                    ),
                    array(
                        'id'    => 'enable',
                        'type'  => 'checkbox',
                        'title' => '是否启用',
                        //'label' => '是否启用?'
                    ),
                    array(
                        'id'      => 'title',
                        'type'    => 'text',
                        'title'   => '标题',                       
                        'attributes' => array(
                            'data-title' => 'title',
                            'placeholder' =>'请输入标题',
                        ),
                    )
                )

            )

        )
    );

    $fields[] = array(
        'name'   => 'selected_options',
        'title'  => '精选栏目设置',
        'icon'   => 'fa fa-home',
        'fields' => array(

        array(
            'type'    => 'group',
            'id'      => 'selected_nav',
            'title'   => '精选栏目',
            'options' => array(
                'repeater'          => true,
                'accordion'         => true,                  
                'button_title'      => '添加',
                'group_title'       => '栏目设置',
                'limit'             => $selected_nav_count,
                'sortable'          => true,
                'closed'            => true,
                
            ),             

            'fields'  => array(    
                
                array(
                    'id'             => 'type',
                    'type'           => 'select',
                    'title'          => '跳转类型',
                    'options'        => array(
                        'apppage'          => '跳转到小程序内页面',
                        'miniapp'     => '跳转其他小程序',
                        'webpage'   => '跳转到网页'
                    ),                      
                    'default'     => 'apppage',                             // optional 
                    'class'       => 'chosen',                          // optional 
                   
                ),    
                array(
                    'id'             => 'jumptype',
                    'type'           => 'select',
                    'title'          => '跳转方式',
                    'after'       => '跳转其他小程序时需选择',
                    'options'        => array(
                        'redirect'          => '切换跳转',
                        'embedded'     => '半屏跳转'                            
                    ),                      
                    'default'     => 'redirect',                             // optional 
                    'class'       => 'chosen',  
                ),           
                array(
                    'id'      => 'appid',
                    'type'    => 'text',
                    'title'   => 'appid',
                    'after'       => '跳转小程序时需填写',   // optional   
                    'attributes' => array(
                        'data-title' => 'title',
                        'placeholder' => '请输入小程序appid'
                    )
                ),                    
                array(
                    'id'    => 'image',
                    'type'  => 'image',
                    'title' => '图片',
                ),
                 array(
                    'id'      => 'path',
                    'type'    => 'text',
                    'title'   => '小程序路径',
                    'after'       => '跳转小程序时需填写。
                        <br/>跳转本小程序的页面路径请以<font color=red>"/pages"</font>开头。
                        <br/>跳转其他小程序的页面路径一般以<font color=red>"pages"</font>开头。
                        <br/>跳转直播间<font color=red>"plugin-private://wx2b03c6e691cd7370/pages/live-player-plugin?room_id=11"</font>开头。room_id换成直播间的房间号',   // optional   
                    'attributes' => array(
                        'data-title' => 'title',
                        'placeholder' => '请输入小程序页面路径'
                    )
                    ),
               
                array(
                    'id'      => 'url',
                    'type'    => 'text',
                    'title'   => 'url地址',
                    'after'       => '跳转到网页需填写。链接包括：小程序业务域名链接，公众号文章链接',   // optional   
                    'attributes' => array(
                        'data-title' => 'title',
                        'placeholder' => '请输入链接'
                    )
                ),
                array(
                    'id'    => 'unassociated',
                    'type'  => 'checkbox',
                    'title' => '网页链接是没有关联小程序的公众号文章',
                    'after'       => '跳转<font color=red>没有关联</font>小程序的公众号文章时选中'
                    //'label' => '是否启用?'
                ),
                array(
                    'id'    => 'enable',
                    'type'  => 'checkbox',
                    'title' => '是否启用',
                    //'label' => '是否启用?'
                ),
                array(
                    'id'      => 'title',
                    'type'    => 'text',
                    'title'   => '标题',                       
                    'attributes' => array(
                        'data-title' => 'title',
                        'placeholder' =>'请输入标题',
                    ),
                )
            )

        )
        

        )
    );
   $options_panel = new Exopite_Simple_Options_Framework($config_submenu, $fields );
   minapper_verify();
}
function cmp_do_output_buffer() {
    ob_start();
}
add_action('init', 'cmp_do_output_buffer');
function minapper_validation_page() {  
    // 移除原有的POST处理逻辑，改为AJAX处理
?> 


<style>
        .Modal {
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -webkit-box-shadow: 0 5px 20px hsla(0, 0%, 7%, .1);
            box-shadow: 0 5px 20px hsla(0, 0%, 7%, .1);
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;

            margin-top: 24px;
            margin-right: 24px;
            max-height: calc(100vh - 48px);
            position: relative;
            -webkit-transition: max-height .8s ease;
            transition: max-height .8s ease;
            width: auto;
            z-index: 1;
        }

        .Modal-inner {
            background: #fff;
            border-radius: 2px;
            overflow: auto;
        }

        .Modal-content {
            -webkit-box-flex: 1;
            -ms-flex: 1 1;
            flex: 1 1;
            line-height: 1.7;
            margin: 0;
            opacity: 1;
            padding: 0;
        }

        .signFlowModal-container,
        .signQr-container {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            justify-content: center;
        }

        .signFlowModal-container {
            overflow: hidden;
            position: relative;
        }

        .signQr-container {
            background-color: #fff;
        }

        .signQr-leftContainer {
            width: 332px;
        }

        .Qrcode-container.smallVersion {
            padding-top: 98px;
        }

        .Qrcode-container {
            position: relative;
            text-align: left;
        }

        .css-k49mnn {
            box-sizing: border-box;
            margin: 0px;
            min-width: 0px;
            color: rgb(68, 68, 68);
            font-size: 16px;
            font-weight: 600;
            line-height: 23px;
        }

        .css-qj3urb {
            box-sizing: border-box;
            margin: 8px 0px 24px;
            min-width: 0px;
            color: rgb(68, 68, 68);
            font-size: 14px;
            line-height: 20px;
        }

        .css-x9rxz4 {
            box-sizing: border-box;
            margin: 24px 0px 0px;
            min-width: 0px;
            color: rgb(68, 68, 68);
            font-size: 14px;
            font-weight: 600;
            line-height: 20px;
        }

        .css-1o2gsjy {
            box-sizing: border-box;
            margin: 0px;
            padding-top: 98px;
            min-width: 0px;
            background-color: rgb(255, 255, 255);
            width: 400px;
            overflow: hidden;
            box-shadow: none;
        }

        .Qrcode-container.smallVersion .Qrcode-img {
            margin-bottom: 40px;
            margin-top: 40px;
        }

        .Qrcode-container.smallVersion .Qrcode-img {
            height: 150px;
            width: 150px;
        }

        .Qrcode-container.smallVersion .Qrcode-qrcode {
            border-radius: 6px;
            height: 150px;
            width: 150px;
            display: block;
        }

        .Qrcode-container.smallVersion .Qrcode-qrcode {
            border: 1px solid #ebebeb;
            padding: 8px;
        }


        .signQr-rightContainer {
            border-left: 1px solid #ebebeb;
        }



        .SignContainer-content {
            margin: 0 auto;

        }

        .SignContainer-inner {
            overflow: hidden;
            position: relative;
        }

        .Login-content {
            padding: 0 24px 30px;
        }

        .SignFlow {
            overflow: hidden;
        }

        .SignFlow-tabs {
            margin-top: 16px;
            text-align: left;
        }

        .SignFlow-tab--active {
            font-synthesis: style;
            font-weight: 600;
        }

        .SignFlow-tab--active {
            color: #121212;
            position: relative;
        }

        .SignFlow-tab {
            cursor: pointer;
            font-size: 16px;
            height: 49px;
            line-height: 46px;
            margin-right: 24px;
        }

        .SignFlow-tab {
            color: #444;
            display: inline-block;
        }

        .SignFlow-tab--active:after {
            background-color: #056de8;
            bottom: 0;
            content: "";
            display: block;
            height: 3px;
            position: absolute;
            width: 100%;
        }

        .Login-content .SignFlow-smsInputContainer {
            margin-top: 11px;
        }

        .Login-content .SignFlow-smsInputContainer {
            border-bottom: 1px solid #ebebeb;
        }

        .SignFlow-smsInputContainer {
            margin-top: 12px;
            position: relative;
        }

        .SignFlow .SignFlow-accountInput,
        .SignFlow .SignFlow-smsInput {
            width: auto;
        }

        .SignFlowInput {
            -webkit-box-flex: 1;
            -ms-flex: 1 1;
            flex: 1 1;
            position: relative;
        }

        .SignContainer-content .Input-wrapper {
            border: none;
            border-radius: 0;
            height: 44px;
            padding: 0;
            width: 100%;
            color: #8590a6;
        }

        .SignFlowInput input.Input {
            height: 48px;
            border: none;
        }

        .Login-content .username-input {
            color: #444;
        }

        input.i7cW1UcwT6ThdhTakqFm {

            line-height: 24px;
        }

        .Button--primary.Button--blue {
            background-color: #056de8;
            color: #fff;
            border: none;
            border-radius: 4px;
        }

        .SignFlow-submitButton {
            height: 36px;
            margin-top: 30px;
            width: 100%;
        }

        .SignContainer-tip {
            font-size: 12px;
            line-height: 19px;
        }

        .SignContainer-tip {
            color: #999;
            padding: 12px 24px 5px;
        }
    </style>

</style>

<div class="wrap">
<h1 class="wp-heading-inline">验证</h1>
<hr class="wp-header-end" />
<!-- 添加消息显示容器 -->
<div id="minapper_message"></div>
    <div class="Modal" tabindex="0">
        <div class="Modal-inner">
            <div class="Modal-content">
                <div>
                    <div class="signFlowModal-container">
                        <div class="signQr-container">
                            <div class="signQr-leftContainer">
                                <div class="Qrcode-container smallVersion">
                                    <div class="css-k49mnn">第1步：关注公众号</div>
                                    <div class="css-qj3urb">扫码关注公众号，发送“ <b>验证码</b> ”获取验证码</div>
                                    <div class="Qrcode-content">
                                        <div class="Qrcode-img">
                                            <img class="Qrcode-qrcode" width="250" height="250" src="https://open.weixin.qq.com/qr/code?username=minapper" alt="二维码">
                                        </div>
                                        <div class="Qrcode-guide-message">
                                            <div class="css-x9rxz4"> 或者微信搜索公众号“ <b>微慕</b> ”</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="signQr-rightContainer">
                                <div class="css-1o2gsjy">
                                    <div class="SignContainer-content">
                                        <div class="SignContainer-inner">
                                            <!-- 修改表单：移除action，添加ID和事件处理 -->
                                            <form method="POST" id="minapper_verify_form" class="SignFlow Login-content">
                                                <div class="css-k49mnn">第2步：验证</div>
                                                <div class="SignFlow-tabs">
                                                    <div class="SignFlow-tab SignFlow-tab--active" role="button" tabindex="0">验证码</div>
                                                </div>
                                                <div class="SignFlow SignFlow-smsInputContainer">
                                                    <div class="SignFlowInput SignFlow-smsInput">
                                                        <label class="Input-wrapper ">
                                                            <input name="minapper_verification_code" type="number" class="Input username-input" placeholder="输入 6 位验证码" value="">
                                                        </label>
                                                    </div>
                                                </div>
                                                <?php wp_nonce_field('minapper_verify', 'minapper_verify_nonce'); ?>
                                                <button type="submit" name="minapper_verify" class="Button SignFlow-submitButton Button--primary Button--blue">
                                                    验证
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="SignContainer-tip">验证后可以访问“扩展设置”页面</div>
                                    <div class="SignContainer-tip">如多次测试无法通过，可以尝试重新关注公众号测试！</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#minapper_verify_form').on('submit', function(e) {
        e.preventDefault();
        
        // 显示加载指示器
        $('#minapper_message').html('<div class="notice notice-info"><p>验证中...</p></div>');
        
        // 发送AJAX请求
        $.ajax({
            type: 'POST',
            url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
            data: {
                action: 'minapper_verify_code',
                minapper_verify_nonce: $('input[name="minapper_verify_nonce"]').val(),
                minapper_verification_code: $('input[name="minapper_verification_code"]').val()
            },
            success: function(response) {
                if (response.success) {
                    // 验证成功，重定向页面
                    window.location.href = response.data.redirect_url;
                } else {
                    // 显示错误信息
                    $('#minapper_message').html('<div class="notice notice-error"><p><strong>' + response.data.message + '</strong></p></div>');
                }
            },
            error: function() {
                $('#minapper_message').html('<div class="notice notice-error"><p><strong>请求失败，请重试</strong></p></div>');
            }
        });
    });
});
</script>

<?php } 

// 注册AJAX处理函数
add_action('wp_ajax_minapper_verify_code', 'minapper_verify_code_ajax_handler');
function minapper_verify_code_ajax_handler() {
    if (!current_user_can('manage_options')) {
        exit;
    }
    // 验证nonce
    $minapper_verify_nonce= isset($_POST['minapper_verify_nonce'])?sanitize_text_field(wp_unslash($_POST['minapper_verify_nonce'])):'';
    if (isset($_POST['minapper_verify'])  &&  wp_verify_nonce($minapper_verify_nonce, 'minapper_verify')) {
        wp_send_json_error(array('message' => '安全验证失败'.$_POST['minapper_verify_nonce']));
    }

    // 检查验证码
    if (empty($_POST['minapper_verification_code'])) {
        wp_send_json_error(array('message' => '缺少验证码'));
    }

    $code = sanitize_text_field(wp_unslash($_POST['minapper_verification_code']));
    $args = array(
        'body' => json_encode(array('code' => $code)),
        'headers' => array('Content-Type' => 'application/json'),
    );
    
    $response = wp_remote_post('https://plus.minapper.com/wp-json/minapper/v1/wechat/verifycode', $args);
    
    if (is_wp_error($response)) {
        wp_send_json_error(array('message' => '请求失败：' . $response->get_error_message()));
    }
    
    $status_code = wp_remote_retrieve_response_code($response);
    if ($status_code !== 200) {
        wp_send_json_error(array('message' => '请求失败，状态码：' . $status_code));
    }
    
    $body = wp_remote_retrieve_body($response);
    $result = json_decode($body, true);
    
    if (empty($result['success'])) {
        wp_send_json_error(array('message' => '无效的验证码'));
    }
    
    // 更新选项并返回重定向URL
    update_option('minapper_weixin_user', array_merge($result, ['last_update' => time()]));
    wp_send_json_success(array(
        'redirect_url' => admin_url('admin.php?page=weixinapp_slug')
    ));
}