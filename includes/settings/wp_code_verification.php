<?php
function minapper_do_output_buffer()
{
    ob_start();
}
add_action('init', 'minapper_do_output_buffer');

function minapper_check_validation()
{
    //delete_transient('minapper_is_validated');

    if (isset($_GET['page']) && ($_GET['page'] == 'weixinapp_slug' || $_GET['page'] == 'minapper_expand_settings_page')) {

        $is_validated = get_transient('minapper_is_validated');
        if (!$is_validated) {
            wp_redirect(admin_url('admin.php?page=minapper_validation_page'));
            exit;
        }
    }
}
add_action('admin_init', 'minapper_check_validation');


function minapper_add_validation_page()
{
    add_submenu_page(
        null,
        __('Validation', 'rest-api-to-miniprogram'),
        __('Validation', 'rest-api-to-miniprogram'),
        'manage_options',
        'minapper_validation_page',
        'minapper_render_validation_page'
    );
}
add_action('admin_menu', 'minapper_add_validation_page');


function minapper_render_validation_page()
{
    //ob_start(); 
?> <style>
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
            height: 120px;
            width: 120px;
        }

        .Qrcode-container.smallVersion .Qrcode-qrcode {
            border-radius: 6px;
            height: 120px;
            width: 120px;
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
            padding: 12px 24px 30px;
        }
    </style>
    <div class="Modal  " tabindex="0">
        <div class="Modal-inner">
            <div class="Modal-content">
                <div>
                    <div class="signFlowModal-container">
                        <div class="signQr-container">
                            <div class="signQr-leftContainer">
                                <div class="Qrcode-container smallVersion">
                                    <div class="css-k49mnn">第1步：关注公众号</div>
                                    <div class="css-qj3urb">扫码关注公众号，发送“ <b>验证码</b> ”获取邀请码</div>
                                    <div class="Qrcode-content">
                                        <div class="Qrcode-img">
                                            <img class="Qrcode-qrcode" width="150" height="150" src="/wp-content/plugins/rest-api-to-miniprogram/includes/images/qrcode.jpg" alt="二维码">
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
                                            <form method="POST" class="SignFlow Login-content">
                                                <div class="css-k49mnn">第2步：验证</div>
                                                <div class="SignFlow-tabs">
                                                    <div class="SignFlow-tab SignFlow-tab--active" role="button" tabindex="0">验证码</div>
                                                </div>
                                                <div class="SignFlow SignFlow-smsInputContainer">
                                                    <div class="SignFlowInput SignFlow-smsInput">
                                                        <label class="Input-wrapper ">
                                                            |<input name="minapper_verification_code" type="number" class="Input  username-input" placeholder="输入 6 位验证码" value="">


                                                        </label>
                                                    </div>
                                                </div>

                                                <button type="submit" name="minapper_verify" class="Button SignFlow-submitButton  Button--primary Button--blue ">
                                                    立即认证
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="SignContainer-tip">验证后可以访问“基础设置”和“扩展设置”页面，如有问题可以联系客服微信：poisonkid 或 iamxjb</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    //$output = ob_get_clean(); 
    //echo $output;


    if (isset($_POST['minapper_verify'])) {
        if (isset($_POST['minapper_verification_code'])) {
            $code = sanitize_text_field($_POST['minapper_verification_code']);

            $args = array(
                'body' => json_encode(array('code' => $code)),
                'headers' => array('Content-Type' => 'application/json'),
            );


            $response = wp_remote_post('https://blog.minapper.com/wp-json/uniapp-builder/v1/validatecode', $args);

          
            
            if (is_array($response) && wp_remote_retrieve_response_code($response) == 200) {
                $body = wp_remote_retrieve_body($response);
                $json = json_decode($body, true);
                
                if ($json['status'] === 'success') {
                      
                    //ob_end_clean();  // 清除缓冲区
                    // update_option('minapper_is_validated', true);
                    set_transient('minapper_is_validated', true, 30 * 24 * 60 * 60);
                    wp_redirect(admin_url('admin.php?page=weixinapp_slug'));
                    exit;
                } else {
                    echo '<p>无效的验证码。</p>';
                }
            } else {
                echo '<p>API 请求失败，状态码：' . wp_remote_retrieve_response_code($response) . '</p>';
            }
        } else {
            echo '<p>缺少验证码。</p>';
        }
    }
}


function minapper_add_plugin_page_settings_link($links)
{
    $is_validated = get_transient('minapper_is_validated', false);


    if ($is_validated) {
        $settings_link = '<a href="admin.php?page=weixinapp_slug">' . __('设置', 'rest-api-to-miniprogram') . '</a>';
        $extension_settings_link = '<a href="admin.php?page=minapper_expand_settings_page">' . __('扩展设置', 'rest-api-to-miniprogram') . '</a>';
        array_unshift($links, $settings_link);
    } else {
        $validation_link = '<a href="admin.php?page=minapper_validation_page">' . __('验证插件', 'rest-api-to-miniprogram') . '</a>';
        array_unshift($links, $validation_link);
    }

    return $links;
}
add_filter('plugin_action_links_rest-api-to-miniprogram/rest-api-to-miniprogram.php', 'minapper_add_plugin_page_settings_link');
