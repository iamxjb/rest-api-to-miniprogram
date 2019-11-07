<?php 

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function weixinapp_create_menu() {
    // 创建新的顶级菜单
    add_menu_page('微慕小程序', '微慕小程序', 'administrator', 'weixinapp_slug', 'weixinapp_settings_page', plugins_url('rest-api-to-miniprogram/includes/images/icon16.png'),null);
    // 调用注册设置函数
    add_action( 'admin_init', 'register_weixinappsettings' );
}

function get_jquery_source() {
        $url = plugins_url('',__FILE__); 
        wp_enqueue_style("tabs", plugins_url()."/rest-api-to-miniprogram/includes/js/tab/tabs.css", false, "1.0", "all");
        wp_enqueue_script("tabs", plugins_url()."/rest-api-to-miniprogram/includes/js/tab/tabs.min.js", false, "1.0");
}

function register_weixinappsettings() {
    // 注册设置
    register_setting( 'weixinapp-group', 'wf_appid' );
    register_setting( 'weixinapp-group', 'wf_secret' );
    register_setting( 'weixinapp-group', 'wf_swipe' );
    
    register_setting( 'weixinapp-group', 'wf_mchid' );
    register_setting( 'weixinapp-group', 'wf_paykey' );
    register_setting( 'weixinapp-group', 'wf_paybody' );

    register_setting( 'weixinapp-group', 'wf_poster_imageurl' );
    register_setting( 'weixinapp-group', 'wf_enable_comment_option' );
    register_setting( 'weixinapp-group', 'wf_enable_comment_check' );

    register_setting( 'weixinapp-group', 'wf_praise_word' );
    register_setting( 'weixinapp-group', 'wf_enterprise_minapp' );

    register_setting( 'weixinapp-group', 'wf_list_ad' );
    register_setting( 'weixinapp-group', 'wf_list_ad_id' );

    register_setting( 'weixinapp-group', 'wf_list_ad_every' );


    register_setting( 'weixinapp-group', 'wf_excitation_ad_id' );

    

    register_setting( 'weixinapp-group', 'wf_detail_ad' );
    register_setting( 'weixinapp-group', 'wf_detail_ad_id' );
    register_setting( 'weixinapp-group', 'wf_about' );
    register_setting( 'weixinapp-group', 'wf_display_categories' );
    
    
   


    




    

    
    
       
    
    
}

function weixinapp_settings_page() {
?>
<div class="wrap">

<h2>微慕小程序设置</h2>


<p>Rest API to miniprogram by <a href="https://www.watch-life.net" target="_blank">守望轩</a>.
<?php

if (!empty($_REQUEST['settings-updated']))
{
    echo '<div id="message" class="updated fade"><p><strong>设置已保存</strong></p></div>';

} 

if (version_compare(PHP_VERSION, '5.6.0', '<=') )
{
    
    echo '<div class="notice notice-error is-dismissible">
    <p><font color="red">提示：php版本小于5.6.0, 插件程序将无法正常使用,当前系统的php版本是:'.PHP_VERSION.'</font></p>
    </div>';

}
?>
<form method="post" action="options.php">
    <div class="responsive-tabs">
    <?php settings_fields( 'weixinapp-group' ); ?>
    <?php do_settings_sections( 'weixinapp-group' ); ?>
    <div class="responsive-tabs">
    <h2> 常规设置</h2>
    <div class="section">
        <table class="form-table">
            <tr valign="top">
            <th scope="row">AppID</th>
            <td><input type="text" name="wf_appid" style="width:400px; height:40px" value="<?php echo esc_attr( get_option('wf_appid') ); ?>" />* </td>
            </tr>
             
            <tr valign="top">
            <th scope="row">AppSecret</th>
            <td><input type="text" name="wf_secret" style="width:400px; height:40px" value="<?php echo esc_attr( get_option('wf_secret') ); ?>" />* </td>
            </tr>

            <tr valign="top">
                            <th scope="row">商户号MCHID</th>
                            <td><input type="text" name="wf_mchid" style="width:400px; height:40px" value="<?php echo esc_attr( get_option('wf_mchid') ); ?>" /> <p style="color: #959595; display:inline">微信支付商户后台获取</p></td>
                        </tr>


                        <tr valign="top">
                            <th scope="row">商户支付密钥key</th>
                            <td><input type="text" name="wf_paykey" style="width:400px; height:40px" value="<?php echo esc_attr( get_option('wf_paykey') ); ?>" /> <p style="color: #959595; display:inline">微信支付商户后台获取</p></td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">支付描述</th>
                            <td><input type="text" name="wf_paybody" style="width:400px; height:40px" value="<?php echo esc_attr( get_option('wf_paybody') ); ?>" /><br /><p style="color: #959595; display:inline">* 商家名称-销售商品类目，例如：守望轩-赞赏</p></td>
                        </tr>


            <tr valign="top">
            <th scope="row">小程序首页滑动文章ID</th>
            <td><input type="text" name="wf_swipe" style="width:400px; height:40px" value="<?php echo esc_attr( get_option('wf_swipe') ); ?>" /><p style="color: #959595; display:inline">* 请用英文半角逗号分隔</p></td>
            </tr>

            <tr valign="top">
                <th scope="row">在小程序里显示的文章分类id</th>
                <td><input type="text" name="wf_display_categories" style="width:400px; height:40px" value="<?php echo esc_attr( get_option('wf_display_categories') ); ?>" />
                <br /><p style="color: #959595 ; display:inline">* 文章分类id,只支持一级分类,请用英文半角逗号分隔，留空则显示所有分类</p>
                    </td>
            </tr>

            <tr valign="top">
            <th scope="row">选择"关于"页面</th>
            <td>
            <select id="wf_about" name="wf_about" >
            <?php

                $mypages = get_pages( array( 'child_of' =>0, 'sort_column' => 'post_date', 'sort_order' => 'desc' ) );
                foreach( $mypages as $page ) {      
                    $title = $page->post_title;
                    $pageId=$page->ID;
                    ?>
                     
               <option  value="<?php echo $pageId;  ?>" <?php echo get_option('wf_about')==$pageId?'selected':''; ?>><?php echo $title ?></option>"
                   <?php }  ?>
            </select>
            </td>
            </tr>

            <tr valign="top">
            <th scope="row">开启小程序的评论</th>
            <td>

                <?php

                $wf_enable_comment_option =get_option('wf_enable_comment_option');            
                $checkbox=empty($wf_enable_comment_option)?'':'checked';
                echo '<input name="wf_enable_comment_option"  type="checkbox"  value="1" '.$checkbox. ' />';
                

                           ?>
                           &emsp;&emsp;&emsp;&emsp;开启评论审核

                <?php
                $wf_enable_comment_check =get_option('wf_enable_comment_check');            
                $checkbox1=empty($wf_enable_comment_check)?'':'checked';
                echo '<input name="wf_enable_comment_check"  type="checkbox"  value="1" '.$checkbox1. ' />';

                ?>
                            </td>
            </tr>     

            <tr valign="top">
            <th scope="row">海报图片默认地址</th>
            <td><input type="text" name="wf_poster_imageurl" style="width:400px; height:40px" value="<?php echo esc_attr( get_option('wf_poster_imageurl') ); ?>" /><br/><p style="color: #959595; display:inline">* 请输完整的图片地址，例如：https://www.watch-life.net/images/poster.jpg</p></td>
            </tr>

            <tr valign="top">
                            <th scope="row">"赞赏"文字调整为</th>
                            <td><input type="text" name="wf_praise_word" placeholder="喜欢" style="width:400px; height:40px" value="<?php echo esc_attr( get_option('wf_praise_word') ); ?>" /><br /><p style="color: #959595; display:inline">* 例如：<code>鼓励</code>,<code>喜欢</code>，<code>稀罕</code>，不要超过两个汉字</p></td>
                        </tr>

            <tr valign="top">
            <th scope="row">小程序是否是企业主体</th>
            <td>
                <?php
                $wf_enterprise_minapp =get_option('wf_enterprise_minapp');            
                $checkbox=empty($wf_enterprise_minapp)?'':'checked';
                echo '<input name="wf_enterprise_minapp"  type="checkbox"  value="1" '.$checkbox. ' />';
                ?><p style="color: #959595; display:inline">* 如果是企业主体的小程序，请勾选</p>
            </td>
        </tr> 
                   
        </table>
    </div>
    <h2>广告设置</h2>
    <div class="section">
    <table class="form-table">
    <tr valign="top">
        <th scope="row">开启文章列表广告</th>
            <td>
                <?php
                $wf_list_ad =get_option('wf_list_ad');            
                $checkbox=empty($wf_list_ad)?'':'checked';
                echo '<input name="wf_list_ad"  type="checkbox"  value="1" '.$checkbox. ' />';
                ?>
                &emsp;&emsp;&emsp;广告id:&emsp;<input type="text" name="wf_list_ad_id" style="width:300px; height:40px" value="<?php echo esc_attr( get_option('wf_list_ad_id') ); ?>" />
                <br/>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;每<input type="number" name="wf_list_ad_every" style="width:40px; height:40px" value="<?php echo esc_attr( get_option('wf_list_ad_every') ); ?>" />条列表展示一条广告<br/><p style="color: #959595; display:inline">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;请输入整数,否则无法正常展示广告</p>
            </td>
            </td>
        </tr>

        <tr valign="top">
        <th scope="row">开启内容详情页广告</th>
            <td>

                <?php
                $wf_detail_ad =get_option('wf_detail_ad');            
                $checkbox=empty($wf_detail_ad)?'':'checked';
                echo '<input name="wf_detail_ad"  type="checkbox"  value="1" '.$checkbox. ' />';
                ?>
                &emsp;&emsp;&emsp;广告id:&emsp;<input type="text" name="wf_detail_ad_id" style="width:300px; height:40px" value="<?php echo esc_attr( get_option('wf_detail_ad_id') ); ?>" />
            </td>
        </tr>

        <tr valign="top">
                        <th scope="row">激励视频广告id</th>
                            <td>                                
                               <input type="text" name="wf_excitation_ad_id" style="width:300px; height:40px" value="<?php echo esc_attr( get_option('wf_excitation_ad_id') ); ?>" />
                            </td>
                        </tr>
</table>
    </div>

    <h2>微慕增强版</h2>
    <div class="section">
        <div style="display: flex; flex-direction: row; margin-bottom: 10px">
            <a href="https://www.minapper.com" target="_blank" style="text-decoration: none"><div style="width:120px; height:32px; background-color: #ff8f3b; border-radius: 4px; color: #fff;display: flex;justify-content: center; align-items: center;margin-right: 16px">微慕官网</div></a>
           <a href="https://mall.minapper.com"  target="_blank" style="text-decoration: none"><div style="width:120px; height:32px; background-color: #fff; border: 1px solid #ff8f3b; border-radius: 4px; box-sizing: border-box; color: #ff8f3b;display: flex;justify-content: center; align-items: center">微慕商城</div></a>
        </div>
                <p style="color: #4c4c4c;text-align:justify; line-height: 2">微慕增强版WordPress小程序是一款，在原守望轩开源小程序（现微慕开源小程序）基础上重新架构、设计、优化过的wordpress多端小程序，性能和用户体验更佳，界面设计更加简洁清新，同时打通<span style="font-weight:bold">微信小程序、QQ小程序、百度小程序、支付宝小程序、头条小程序...真正实现一站多端</span>，可使用微信扫描下方小程序码直接体验：</p>
        <div>
            <img src="https://plusimg.minapper.com/wp-content/uploads/2019/08/1565183497-%E5%BE%AE%E6%85%95%E6%B5%B7%E6%8A%A5-2019%E5%B9%B47%E6%9C%88.jpg" alt="微慕增强版" width="100%"></img>
        </div>
    </div>

    <h2>微慕版专业版</h2>
    <div class="section">
        <div style="display: flex; flex-direction: row; margin-bottom: 10px">
            <a href="https://www.minapper.com" target="_blank" style="text-decoration: none"><div style="width:120px; height:32px; background-color: #fc6e6e; border-radius: 4px; color: #fff;display: flex;justify-content: center; align-items: center;margin-right: 16px">微慕官网</div></a>
           <a href="https://mall.minapper.com"  target="_blank" style="text-decoration: none"><div style="width:120px; height:32px; background-color: #fff; border: 1px solid #fc6e6e; border-radius: 4px; box-sizing: border-box; color: #fc6e6e;display: flex;justify-content: center; align-items: center">微慕商城</div></a>
        </div>
                <p style="color: #4c4c4c;text-align:justify; line-height: 2">微慕版专业版WordPress小程序和插件，在“守望轩”开源小程序的基础上，架构完全重构，在性能上大幅度优化，增加了<span style="font-weight:bold">动态圈子、积分系统、文章投稿、发布动态、付费阅读、会员权限、多种图文列表样式、预约表单、模板消息</span>等功能，并且免费提供标准版、旅游版、图片版、企业版4套前端模板，可使用微信扫描下方小程序码直接体验：</p>
        <div>
            <img src="https://www.watch-life.net/images/minapper-pro.jpg" alt="微慕专业版" width="100%"></img>

        </div>
    </div>

    



 </div>

    
    <?php submit_button();?>
</form>
 <?php get_jquery_source(); ?>
            <script>
               jQuery(document).ready(function($) {
                RESPONSIVEUI.responsiveTabs();
                // if($("input[name=post_meta]").attr('checked')) {
                //     $("#section_meta_list").addClass("hide");
                // }
            });
            </script>
</div>
<?php }  