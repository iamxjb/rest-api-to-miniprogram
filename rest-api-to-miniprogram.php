<?php
/*
Plugin Name: REST API TO MiniProgram
Plugin URI: http://www.watch-life.net
Description: 为微信小程序、app提供定制WordPress REST API 输出.支持微信支付、微信小程序模板消息.
Version: 0.8
Author: jianbo
Author URI: http://www.minapper.com
License: GPL v3
WordPress requires at least: 4.7.0
*/


define('REST_API_TO_MINIPROGRAM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('REST_API_TO_MINIPROGRAM_PLUGIN_FILE',__FILE__);

include(REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/ram-util.php' );
include(REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/ram-api.php' );
include(REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/ram-weixin-api.php');
include(REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/settings/wp-wechat-config.php');
include(REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/filter/ram-custom-comment-fields.php');
include(REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/filter/ram-custom-content.php');
include(REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/filter/ram-custom-post-fields.php');
include(REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/filter/ram-custom-category.php');

if ( ! class_exists( 'RestAPIMiniProgram' ) ) {

    class RestAPIMiniProgram {
        public $wxapi = null;
        public function __construct() {
            //禁止访问的 rest api
            add_filter( 'rest_endpoints', function( $endpoints ){
                if ( isset( $endpoints['/wp/v2/users'] ) ) {
                    unset( $endpoints['/wp/v2/users'] );
                }
                if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
                    unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
                }                
                return $endpoints;
            });

            // add_action( 'admin_init', 'register_restAPIWechatsettings' );
            // add_action('admin_menu', 'restAPIWechat_config_menu');

            //定制化内容输出，对pc端和api都生效
            add_filter( 'the_content', 'custocm_content_filter' );
            //对文章的自定义输出
            add_filter( 'rest_prepare_post', 'custom_post_fields', 10, 3 );            
            //对评论的自定义输出
            add_filter( 'rest_prepare_comment', 'custom_comment_fields', 10, 3 );
            add_filter( 'rest_prepare_category', 'custom_fields_rest_prepare_category', 10, 3 ); //获取分类的封面图片



            //更新浏览次数（pc）
            add_action('wp_head', 'addPostPageviews');

            //获取浏览次数（pc）
            //add_filter('raw_post_views', 'post_views');

            
            // 管理配置 
            if ( is_admin() ) {             
                
                //new WP_Category_Config();
                add_action('admin_menu', 'weixinapp_create_menu');
                add_filter( 'plugin_action_links', 'ram_plugin_action_links', 10, 2 );
                 
            }

            new RAM_API();//api
            $this->wxapi = new RAW_Weixin_API();


        }

        

    }


    // 实例化并加入全局变量
    $GLOBALS['RestAPIMiniProgram'] = new RestAPIMiniProgram();
    
    function RAW() {
        
        if( ! isset( $GLOBALS['RestAPIMiniProgram'] ) ) {
            $GLOBALS['RestAPIMiniProgram'] = new RestAPIMiniProgram();
        }
        
        return $GLOBALS['RestAPIMiniProgram'];
    }

    function ram_plugin_action_links( $links, $file ) {
        if ( plugin_basename( __FILE__ ) !== $file ) {
            return $links;
        }

        $settings_link = '<a href="https://www.minapper.com/" target="_blank"> <span style="color:#d54e21; font-weight:bold;">' . esc_html__( '升级专业版', 'REST API TO MiniProgram' ) . '</span></a>';

        array_unshift( $links, $settings_link );


        $settings_link = '<a href="https://www.watch-life.net/" target="_blank"> <span style="color:green; font-weight:bold;">' . esc_html__( '技术支持', 'REST API TO MiniProgram' ) . '</span></a>';

        array_unshift( $links, $settings_link );

        $settings_link = '<a href="admin.php?page=weixinapp_slug">' . esc_html__( '设置', 'REST API TO MiniProgram' ) . '</a>';

        array_unshift( $links, $settings_link );

        return $links;
    }

}
