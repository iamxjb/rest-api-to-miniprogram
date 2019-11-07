<?php

if ( ! defined( 'ABSPATH' ) ) exit;
    function wp_post_config() {

        add_action( 'add_meta_boxes','wf_add_post_fields');
        // add_action( 'post_submitbox_misc_actions', array( $this,'raw_post_pay_required' ));
        add_action( 'save_post', 'wf_save_post_fields'); 

    }

    function wf_add_post_fields()
    {     
        $url=$_SERVER['PHP_SELF'];
        if(strpos($url,'post-new.php') !=false)
        {
            return;
        }
        add_meta_box(
            'wf_post_fields_box_id',
            '激励视频',
            'wf_post_fields_box',
            'post',
            'normal',
            'high'
        );

    }

    function wf_post_fields_box()
    {
        
        $postId = get_the_ID();        
        wp_nonce_field( basename( __FILE__ ), 'wf_add_post_fields_nonce' );
        $excitation=empty(get_post_meta($postId,'_excitation',true))?0:get_post_meta($postId,'_excitation',true);
        ?>
        <div class="misc-pub-section misc-pub-section-last">
        <label style="color: red"><input type="checkbox" value="1" id="excitation"  name="excitation" <?php echo $excitation==1?'checked':'' ;?>/><strong>启用激励视频</strong></label>
        </div> 

        <?php

    }

    function wf_save_post_fields($postId)
    {
        //自动保存不处理
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if ( ! isset( $_POST['wf_add_post_fields_nonce'] ) || ! wp_verify_nonce( $_POST['wf_add_post_fields_nonce'], basename( __FILE__ ) ) )
            return;

        

        $excitation=isset( $_POST['excitation'])?(int)$_POST['excitation']:0;

        update_post_meta($postId,'_excitation',$excitation); 

        
       
    }
