<?php
/*
Plugin Name: REST API TO MiniProgram 微慕小程序
Plugin URI: https://www.minapper.com
Description: 为微慕小程序提供定制化WordPress REST API json 输出
Version: 5.1.2
Author: jianbo
Author URI: https://www.minapper.com
License: Apache-2.0
 License URI: https://www.apache.org/licenses/LICENSE-2.0
WordPress requires at least: 5.0.5
*/
define('REST_API_TO_MINIPROGRAM_PLUGIN_DIR', plugin_dir_path(__FILE__));
const REST_API_TO_MINIPROGRAM_PLUGIN_NAME='rest-api-to-miniprogram';
define('REST_API_TO_MINIPROGRAM_PLUGIN_URL',plugins_url(REST_API_TO_MINIPROGRAM_PLUGIN_NAME.'/', dirname(__FILE__)));
include(REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/ram-util.php' );
include(REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/ram-api.php' );
include(REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/ram-weixin-api.php');
include(REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/settings/wp-wechat-config.php');
include(REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/settings/wp-post-config.php');
include(REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/settings/wp-tinymce-add-button.php');
include(REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/filter/ram-custom-comment-fields.php');
include(REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/filter/ram-custom-content.php');
include(REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/filter/ram-custom-post-fields.php');
include(REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/filter/ram-custom-category.php');
include(REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/filter/ram-custom-users-columns.php');
include(REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/filter/ram-custom-category-rows.php');
include(REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/filter/ram-custom-posts-rows.php');
include(REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/admin/exopite-simple-options/exopite-simple-options-framework-class.php');
include(REST_API_TO_MINIPROGRAM_PLUGIN_DIR . 'includes/settings/wp-wechat-admin.php');
if ( ! class_exists( 'RestAPIMiniProgram' ) ) {

    class RestAPIMiniProgram {
        public $wxapi = null;
        public function __construct() {            
            //定制化内容输出，对pc端和api都生效
            add_filter( 'the_content', 'custocm_content_filter' );
            //对文章的自定义输出
            //add_filter( 'rest_prepare_post', 'custom_post_fields', 10, 3 ); 
            //对页面的自定义输出
			add_filter( 'rest_prepare_page', 'custom_post_fields', 10, 3 );           
            //对评论的自定义输出
            add_filter( 'rest_prepare_comment', 'custom_comment_fields', 10, 3 );
            add_filter( 'rest_prepare_category', 'custom_fields_rest_prepare_category', 10, 3 ); //获取分类的封面图片

            //定义用户列表
            add_filter( 'manage_users_columns', 'ram_users_columns' );
			add_action( 'manage_users_custom_column', 'output_ram_users_columns', 10, 3 );
            add_filter( "manage_users_sortable_columns", 'ram_users_sortable_columns' );
			add_action( 'pre_user_query', 'ram_users_search_order' );
            
            //给TinyMCE编辑器增加A标签按钮
			add_action('after_wp_tiny_mce', 'add_tinyMCE_minapper_button');

            
			//文章页显示自定义列
			add_filter( 'manage_posts_columns' , 'ram_posts_columns' );
			add_action( 'manage_posts_custom_column' , 'output_ram_posts_custom_columns', 10, 3 );


            //页面显示自定义列
			add_filter( 'manage_pages_columns' , 'ram_pages_columns' );
			add_action( 'manage_pages_custom_column' , 'output_ram_pages_custom_columns', 10, 3 );
            add_filter( 'bulk_actions-edit-post', 'ram_posts_custom_bulk_actions' );
            add_filter( 'handle_bulk_actions-edit-post', 'ram_posts_custom_bulk_actions_handler', 10, 3 );
 



            //分类目录页自定义列
			add_filter('manage_edit-category_columns' , 'ram_custom_taxonomy_columns');
			add_filter( 'manage_category_custom_column', 'ram_custom_taxonomy_columns_content', 10, 3 );
            //更新浏览次数（pc）
            add_action('wp_head', 'addPostPageviews');

            //获取浏览次数（pc）
            //add_filter('raw_post_views', 'post_views');

            //WordPress 禁止符号转码和页面提速(参考：https://mp.weixin.qq.com/s/nqoZ5LOIEznbxgpqXSxwNQ)
			remove_filter('the_content', 'wptexturize');    //禁止内容转码

            //注册自定义相册短代码
			add_shortcode ('gallery' , 'minapper_gallery');
			

            
            // 管理配置 
            if ( is_admin() ) {             
                
                //new WP_Category_Config();
              add_action( 'admin_enqueue_scripts', 'ram_admin_style', 9999 );
              add_action('admin_menu', 'weixinapp_create_menu');               
              add_filter( 'plugin_action_links', 'ram_plugin_action_links', 10, 2 );
              wp_post_config();
                 
            }

            new RAM_API();//api
            $this->wxapi = new RAM_Weixin_API();


        }

        

    }


    // 实例化并加入全局变量
    $GLOBALS['RestAPIMiniProgram'] = new RestAPIMiniProgram();
    
    function RAM() {
        
        if( ! isset( $GLOBALS['RestAPIMiniProgram'] ) ) {
            $GLOBALS['RestAPIMiniProgram'] = new RestAPIMiniProgram();
        }
        
        return $GLOBALS['RestAPIMiniProgram'];
    }

    function ram_admin_style() {
		wp_enqueue_style( 'raw-admin-css', REST_API_TO_MINIPROGRAM_PLUGIN_URL. 'includes/css/menu.css', array(),'4.0.4' );
	}

    function ram_plugin_action_links( $links, $file ) {
        if ( plugin_basename( __FILE__ ) !== $file ) {
            return $links;
        }

        $settings_link = '<a href="https://www.minapper.com/" target="_blank"> <span style="color:#d54e21; font-weight:bold;">' . esc_html__( '升级增强版', 'rest-api-to-miniprogram' ) . '</span></a>';

        array_unshift( $links, $settings_link );

        $settings_link = '<a href="https://www.minapper.com/" target="_blank"> <span style="color:#d54e21; font-weight:bold;">' . esc_html__( '升级专业版', 'rest-api-to-miniprogram' ) . '</span></a>';

        array_unshift( $links, $settings_link );


        $settings_link = '<a href="https://www.minapper.com/" target="_blank"> <span style="color:green; font-weight:bold;">' . esc_html__( '技术支持', 'rest-api-to-miniprogram' ) . '</span></a>';

        array_unshift( $links, $settings_link );

        $settings_link = '<a href="admin.php?page=weixinapp_slug">' . esc_html__( '设置', 'rest-api-to-miniprogram' ) . '</a>';        

        array_unshift( $links, $settings_link );

        return $links;
    }

    function minapper_gallery($atts, $content)
    {

        $minappergallery = "";    


        $web_gallery_style=get_option("wf_web_gallery_style");       
        extract(shortcode_atts(array(
            'size' => '',
            'ids' => ''
        ), $atts));
        $img = '';
        $images=[];
        if(!empty($ids)) {
                $ids = explode(',', $ids);           
                $realwidth = '';
                $real_height = '';
                $i = 0;
                foreach ($ids as $id) {
                    $image = wp_get_attachment_image_src((int)$id, 'full');
                    $img = $i == 0 ? $img . $image[0] : $img . ',' . $image[0];
                    $realwidth = $i == 0 ? $realwidth . $image[1] : $realwidth . ',' . $image[1];
                    $real_height = $i == 0 ? $real_height . $image[2] : $real_height . ',' . $image[2];
                    $i++;
                    $images[] = $image[0];
                }
            
            } 
        if (is_single() || is_home() || is_feed()) {        
            $attr = array(
                'size' => $size,
                'ids' => $ids
            );
            if($web_gallery_style=='wp')
            {
                $minappergallery =gallery_shortcode($attr, '');
            }
            elseif($web_gallery_style=='swiper')
            {
                // phpcs:disable WordPress.WP.EnqueuedResourceParameters.NotInFooter
                wp_enqueue_style('minappergallerycss', plugins_url() . '/' . REST_API_TO_MINIPROGRAM_PLUGIN_NAME . '/includes/css/gallery.css', false, '1.0', 'all');
                wp_enqueue_script('minappergalleryjs',  plugins_url() . '/' . REST_API_TO_MINIPROGRAM_PLUGIN_NAME . '/includes/js/gallery.js', true, '1.0');
                // phpcs:enable WordPress.WP.EnqueuedResourceParameters.NotInFooter
                $minappergallery .='<div class="gallery-container">
            <div class="gallery-viewport">
                <div class="slides-container">';

                foreach ($images as $item) {
                    $minappergallery.='
                    <div class="slide"><img data-src="'. $item. '" ></div> ';
                }
            $minappergallery.=' 
                </div>
            </div>
            <button class="nav-button prev">❮</button>
            <button class="nav-button next">❯</button>
            <div class="navigation-dots"></div>
        </div>';
            }
        
        
        } else {
            $minappergallery = '<minappergallery images="' . $img . '"  real-width="' . $realwidth . '"  real_height="' . $real_height . '">';
        }
        return $minappergallery;
    }

}
