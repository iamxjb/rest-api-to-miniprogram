<?php 

function custom_fields_rest_prepare_category( $data, $item, $request ) { 
   
        

    $category_thumbnail_image='';
    $temp='';
    $openid= $request["openid"];
    $subscription =getSubscription($openid);
    $id =(string)$item->term_id;
    if(empty($subscription))
    {
        $data->data['subimg'] ="subscription.png"; 
        $data->data['subflag'] ="0"; 
    }
    else
    {
        if(array_search($id,$subscription ))
        {        
            $data->data['subimg'] ="subscription-on.png"; 
            $data->data['subflag'] ="1"; 
        }
        else
        {
            $data->data['subimg'] ="subscription.png"; 
            $data->data['subflag'] ="0"; 

        }
    }
    

    if($temp=get_term_meta($item->term_id,'catcover',true))
    {
        $category_thumbnail_image=$temp;
      
    }
    elseif($temp=get_term_meta($item->term_id,'thumbnail',true));
    {
        $category_thumbnail_image=$temp;
    }
    
    $data->data['category_thumbnail_image'] =$category_thumbnail_image;    
    return $data;
}

function getSubscription($openid)
    {
        global $wpdb;        
        $user_id =0;        
        $user = get_user_by('login', $openid);
        $subscription= array();
        if($user)
        {
            $user_id = $user->ID;
            $usermeta = get_user_meta($user_id);
            if (!empty($usermeta))
            {                        
                if(!empty($usermeta['wl_sub']))
                {
                    $subscription=$usermeta['wl_sub'];
                } 
            }
            
        } 
        return $subscription;

    }


 


/*********   给分类添加微信小程序封面 *********/
?>

<?php 

add_action( 'category_edit_form_fields', 'weixin_edit_term_catcover_field' );
function weixin_edit_term_catcover_field( $term ) {
    $default = '';
    $catcover   = get_term_meta( $term->term_id, 'catcover', true );
    if ( function_exists( 'wp_enqueue_media' ) ) {
        wp_enqueue_media();
    }
    //phpcs:disable WordPress.WP.EnqueuedResourceParameters.NotInFooter
    wp_enqueue_script('rawscript', plugins_url().'/'.REST_API_TO_MINIPROGRAM_PLUGIN_NAME.'/includes/js/script.js',false, '1.0');
    //phpcs:enable WordPress.WP.EnqueuedResourceParameters.NotInFooter
    if ( ! $catcover )
        $catcover = $default; ?>

    <tr class="form-field weixin-app-term-catcover-wrap">
        <th scope="row"><label for="weixin-app-term-catcover">微信小程序封面</label></th>
        <td>
            <?php
            
            //echo wp_nonce_field( basename( __FILE__ ), 'weixin_app_term_catcover_nonce' ); 
            
            ?>
            <input type="url" name="weixin_app_term_catcover" id="weixin-app-term-catcover" class="type-image regular-text" value="<?php echo esc_attr( $catcover ); ?>" data-default-catcover="<?php echo esc_attr( $default ); ?>" />
            <input id="weixin_app_term_catcover-btn" class="button im-upload" type="button" value="选择图片" />
        </td>
    </tr>
<?php }


add_action( 'create_category', 'weixin_app_save_term_catcover' );
add_action( 'edit_category',   'weixin_app_save_term_catcover' );

function weixin_app_save_term_catcover( $term_id ) {
    //phpcs:disable WordPress.Security.NonceVerification.Missing
    $catcover = isset( $_POST['weixin_app_term_catcover'] )?sanitize_text_field(wp_unslash($_POST['weixin_app_term_catcover'])) : '';
    //phpcs:enable
    if ( '' === $catcover ) {
        delete_term_meta( $term_id, 'catcover' );
    } else {
        update_term_meta( $term_id, 'catcover', $catcover );
    }
}

/*********  *********/