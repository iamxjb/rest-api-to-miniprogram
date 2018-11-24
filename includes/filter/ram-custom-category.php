<?php 

function custom_fields_rest_prepare_category( $data, $item, $request ) {      
    $category_thumbnail_image='';
    $temp='';
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


/*********   给分类添加微信小程序封面 *********/

add_action( 'category_add_form_fields', 'weixin_new_term_catcover_field' );
function weixin_new_term_catcover_field() {
    wp_nonce_field( basename( __FILE__ ), 'weixin_app_term_catcover_nonce' ); ?>

    <div class="form-field weixin-app-term-catcover-wrap">
        <label for="weixin-app-term-catcover">微信小程序封面</label>
        <input type="url" name="weixin_app_term_catcover" id="weixin-app-term-catcover"  class="type-image regular-text" data-default-catcover="" />
    </div>
<?php }
add_action( 'category_edit_form_fields', 'weixin_edit_term_catcover_field' );
function weixin_edit_term_catcover_field( $term ) {
    $default = '';
    $catcover   = get_term_meta( $term->term_id, 'catcover', true );

    if ( ! $catcover )
        $catcover = $default; ?>

    <tr class="form-field weixin-app-term-catcover-wrap">
        <th scope="row"><label for="weixin-app-term-catcover">微信小程序封面</label></th>
        <td>
            <?php echo wp_nonce_field( basename( __FILE__ ), 'weixin_app_term_catcover_nonce' ); ?>
            <input type="url" name="weixin_app_term_catcover" id="weixin-app-term-catcover" class="type-image regular-text" value="<?php echo esc_attr( $catcover ); ?>" data-default-catcover="<?php echo esc_attr( $default ); ?>" />
        </td>
    </tr>
<?php }

add_action( 'create_category', 'weixin_app_save_term_catcover' );
add_action( 'edit_category',   'weixin_app_save_term_catcover' );

function weixin_app_save_term_catcover( $term_id ) {
    if ( ! isset( $_POST['weixin_app_term_catcover_nonce'] ) || ! wp_verify_nonce( $_POST['weixin_app_term_catcover_nonce'], basename( __FILE__ ) ) )
        return;

    $catcover = isset( $_POST['weixin_app_term_catcover'] ) ? $_POST['weixin_app_term_catcover'] : '';

    if ( '' === $catcover ) {
        delete_term_meta( $term_id, 'catcover' );
    } else {
        update_term_meta( $term_id, 'catcover', $catcover );
    }
}

/*********  *********/