<?php
//禁止直接访问
if ( ! defined( 'ABSPATH' ) ) exit; 
function add_tinyMCE_minapper_button($mce_settings) {
global $pagenow;
    if (is_admin() && ( $pagenow == 'post-new.php' || $pagenow == 'post.php' ) && wp_script_is('quicktags')) {
?>
    <script type="text/javascript">   
    QTags.addButton( 'linkPlusCode', 'Link+', '<a href="链接地址"  appid="跳转小程序appid"  path="跳转小程序页面路径"  redirectype="跳转类型（miniapp 其他小程序,webpage 网页,apppage 本小程序页面）"" jumptype="跳转方式(跳转其他小程序时填写 redirect 切换跳转,embedded 半屏跳转）" unassociated="网页链接是没有关联小程序的公众号文章(是 1 不是 0）">','</a>' );
    </script>
<?php
    }
}