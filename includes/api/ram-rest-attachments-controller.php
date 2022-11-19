<?php

use function PHPSTORM_META\elementType;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class RAM_REST_Attachments_Controller  extends WP_REST_Controller{

    public function __construct() {
        
        $this->namespace     = 'watch-life-net/v1';
        $this->resource_name = 'attachments';
    }

    public function register_routes() {
        register_rest_route( $this->namespace, '/' . $this->resource_name, array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'POST',
                'callback'  => array( $this, 'create_item' ),
                'permission_callback' => array( $this, 'create_item_permissions_check' ),
                'args'               => array(                   
                    'js_code' => array(
                        'required' => true
                    )
                )
                 
            ),
            // Register our schema callback.
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );

        
     
     }

     /**
     * Creates a single attachment.
     *
     * @since 4.7.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response Response object on success, WP_Error object on failure.
     */
    public function create_item( $request ) {
        // Get the file via $_FILES or raw data.
        $files = $request->get_file_params();
        $js_code= $request['js_code']; 
        $api_result=jscode2session($js_code);
        if($api_result['errcode'] !=0)
        {
            return new WP_Error( 'error', $api_result['errmsg'], array( 'status' => 500 ) );
        }
        $openId = $api_result['openid']; 
        $user = get_user_by('login', $openId); 
        if(empty($user)) {   
            return new WP_Error('error', '用户参数错误', array( 'status' => 502 ) );
        }
        $userId=(int)$user->ID;
        $imagestype =isset($request['imagestype'])?$request['imagestype']:"";        
        $enableUpdateAvatarCount =getEnableUpdateAvatarCount($userId);       
        if($enableUpdateAvatarCount <1 && $imagestype=="updateAvatar")
        {
            return new WP_Error('error', '本年可修改次数为0次', array( 'status' => 200 ) );
        }
        $headers = $request->get_headers();
        if (!empty( $files)) {
            $file = $this->upload_from_file( $files, $headers);
            if (is_wp_error($file)) {
                $message =$file->get_error_message();
            return new WP_Error( 'error', $message, array( 'status' => 500 ) );            
            }
            $url     = $file['url'];
            $info = pathinfo($file['file']);
            $fileName =!empty($request['fileName'])?$request['fileName']:$info['filename'];
            $fileType=$file['type'];
            $attachment = array(
                'guid'           => $url,                
                'post_mime_type' => $fileType, 
                'post_title'     => $fileName,
                'post_name' => $info['filename'],
                'post_content'   => '',
                'post_status'    => 'inherit',
                'post_author' => $userId,
                "comment_status"=>"closed"
              );

              $attached_file=ltrim( wp_upload_dir()['subdir'],'/') .'/'.$info['filename'] ;
              $attachmentPostId = wp_insert_attachment($attachment,$attached_file);

            if(!empty($attachmentPostId))
            {
                 update_post_meta($attachmentPostId,'_wp_attached_file', ltrim( wp_upload_dir()['subdir'],'/') .'/'.$info['basename'] );
                 $basename=$info['basename'];
                 $_filename = wp_upload_dir()['path'].'/'.$basename;
                 require_once( ABSPATH . 'wp-admin/includes/image.php' );
                 require_once( ABSPATH . 'wp-admin/includes/media.php' );
                
                  //加入以下两行方法，可以在媒体库显示图片的缩略图
                 $attach_data = wp_generate_attachment_metadata( $attachmentPostId, $_filename);
                 wp_update_attachment_metadata( $attachmentPostId, $attach_data );
                
                if($imagestype=="zanimage")
                {
                    update_user_meta($userId,'zanimage',$url);
                }

                if($imagestype=="updateAvatar")
                {
                 
                    $updateCount=getUpdateAvatarCount($userId);
                    $updateCount +=1;
                    setUpdateAvatarCount($userId,$updateCount);
                    if (delete_user_meta($userId, 'avatar') ) {
                        update_user_meta($userId,'avatar', $url);
                    }
            
                }
            }

            $enableUpdateAvatarCount =getEnableUpdateAvatarCount($userId);   
            $response = array('success' => true ,'message'=>'更新成功','avatar'=>$url,'avatarUrl'=>get_user_meta( $userId, 'avatar', true),'enableUpdateAvatarCount'=>$enableUpdateAvatarCount);
            $response = rest_ensure_response( $response );
            return $response; 
            
        }
        else{
            return new WP_Error( 'error', '参数错误', array( 'status' => 500 ) );
        }
    }


    /**
     * Handles an upload via multipart/form-data ($_FILES).
     *
     * @since 4.7.0
     *
     * @param array $files   Data from the `$_FILES` superglobal.
     * @param array $headers HTTP headers from the request.
     * @return array|WP_Error Data from wp_handle_upload().
     */
    protected function upload_from_file( $files, $headers ) {
        if ( empty( $files ) ) {
            return new WP_Error( 'rest_upload_no_data', __( 'No data supplied.' ), array( 'status' => 400 ) );
        }

        // Verify hash, if given.
        if ( ! empty( $headers['content_md5'] ) ) {
            $content_md5 = array_shift( $headers['content_md5'] );
            $expected    = trim( $content_md5 );
            $actual      = md5_file( $files['file']['tmp_name'] );

            if ( $expected !== $actual ) {
                return new WP_Error( 'rest_upload_hash_mismatch', __( 'Content hash did not match expected.' ), array( 'status' => 412 ) );
            }
        }

        $mimes = array(
             'txt|asc|c|cc|h'=>'text/plain',
             'jpg|jpeg|jpe' => 'image/jpeg',
             'gif' => 'image/gif',
             'png' => 'image/png',
             'bmp' => 'image/bmp',
             'tif|tiff' => 'image/tiff',
             'mp4|m4v' => 'video/mp4',
             'mp3|m4a' => 'audio/mpeg',
             'aac' => 'audio/aac',
             'wav' => 'audio/wav',
             'pdf' => 'application/pdf',
             'doc' => 'application/msword',
             'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
             'xla|xls|xlt|xlw' => 'application/vnd.ms-excel',
             'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
             'pot|pps|ppt' => 'application/vnd.ms-powerpoint',
             'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
        );

        // Pass off to WP to handle the actual upload.
        $overrides = array(
            'test_form'   => false,'mimes' => $mimes
        );

        // Bypasses is_uploaded_file() when running unit tests.
        if ( defined( 'DIR_TESTDATA' ) && DIR_TESTDATA ) {
            $overrides['action'] = 'wp_handle_mock_upload';
        }

        /** Include admin functions to get access to wp_handle_upload() */
        //require_once ABSPATH . 'wp-admin/includes/admin.php';
        //
        if ( ! function_exists( 'wp_handle_upload' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
    }

        $file = wp_handle_upload( $files['file'], $overrides );

        if ( isset( $file['error'] ) ) {
            return new WP_Error( 'rest_upload_unknown_error', $file['error'], array( 'status' => 500 ) );
        }

        return $file;
    }


    /**
     * Prepares a single attachment for create or update.
     *
     * @since 4.7.0
     *
     * @param WP_REST_Request $request Request object.
     * @return WP_Error|stdClass $prepared_attachment Post object.
     */
    protected function prepare_item_for_database( $request ) {
       
        // Attachment caption (post_excerpt internally)
        if ( isset( $request['caption'] ) &&  is_string( $request['caption']) ) {
            $prepared_attachment->post_excerpt = $request['caption'];
        }

        // Attachment description (post_content internally)
        if ( isset( $request['description'] ) &&  is_string( $request['description'] ) ) {
            $prepared_attachment->post_content = $request['description'];
        }

        return $prepared_attachment;
    }
     /**
     * Checks if a given request has access to create an attachment.
     *
     * @since 4.7.0
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|true Boolean true if the attachment may be created, or a WP_Error if not.
     */
    public function create_item_permissions_check( $request ) {      
       
        return true;
    }

    public function get_item_permissions_check( $request ) {      
        
        return true;
    }


    public function delete_item_permissions_check( $request ) {      
        $sessionId=isset($request['sessionid'])?$request['sessionid']:'';
        $userId =isset($request['userid'])? (int)$request['userid']:0;
        $checkUser =RAW_Util::checkUser($sessionId,$userId);
        
        if(!$checkUser)
        {
            return  RAW_Util::errorUserMessage();
        }

        $postId =isset($request['id'])?(int)$request['id']:0;
       
       if( $postId== 0 || !is_int($postId)|| get_post($postId)==null)
       {

           return new WP_Error( 'error', 'postid参数错误', array( 'status' => 400 ) );

       }   
        return true;
    }

    public function create_my_item_permissions_check( $request ) {      
        $sessionId=isset($request['sessionid'])?$request['sessionid']:'';
        $userId =isset($request['userid'])? (int)$request['userid']:0;
        $checkUser =RAW_Util::checkUser($sessionId,$userId);
        
        if(!$checkUser)
        {
            return  RAW_Util::errorUserMessage();
        }
   
        return true;
    }

    public function get_media_permissions_check( $request ) {      
        
   
        return true;
    }

    public function delete_invite_qrcodeimg_permissions_check( $request ) {      
        
        $invitecode=$request['invitecode'];
        global $wpdb;		
		$wpdb->minapper_weixin_users= $wpdb->prefix.'minapper_weixin_users';

        $sql =$wpdb->prepare("select count(1) from ".$wpdb->minapper_weixin_users." where invitecode=%s",$invitecode);
		$count =(int)$wpdb->get_var($sql);
        if($count == 0)
        {
            return new WP_Error( 'error', '参数错误', array( 'status' => 400 ) );

        }
        return true;
    }


    

   
  
  }