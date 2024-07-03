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
   //$options_panel = new Exopite_Simple_Options_Framework($license_submenu, $fields );
   
   //add_submenu_page('minapper_plus_settings_page', "授权激活", "授权激活", "administrator", 'minapper_license_slug', 'minapper_license_page');
   

}