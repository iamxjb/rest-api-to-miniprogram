<?php
//禁止直接访问
if ( ! defined( 'ABSPATH' ) ) exit; 
function add_tinyMCE_minapper_button($mce_settings) {
global $pagenow;
    if (is_admin() && ( $pagenow == 'post-new.php' || $pagenow == 'post.php' ) && wp_script_is('quicktags')) {
?>
    <script type="text/javascript">   
    QTags.addButton( 'linkPlusCode', 'Link+', '<a href=""  appid=""  path=""  redirectype="" jumptype="">','</a>' );
    </script>
<?php
    }
}