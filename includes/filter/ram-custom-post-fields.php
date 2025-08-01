<?php 
//禁止直接访问
if ( ! defined( 'ABSPATH' ) ) exit;

function custom_post_fields( $data, $post, $request) { 

  $uri=isset( $_SERVER['REQUEST_URI'])?sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])):'';
  if($uri !='' && strpos($uri, 'watch-life-net/v1/posts') !== false)
  {
      
        return $data;
  }
    global $wpdb;
    $_data = $data->data; 
    $post_id =$post->ID;

 

    //$content =get_the_content();
    $content=$_data['content']['rendered'];
    $content_protected=$_data['content']['protected'];
    $raw=empty($_data['content']['raw'])?'':$_data['content']['raw'];

   
     
     $siteurl = get_option('siteurl');
     $upload_dir = wp_upload_dir();
     $content = str_replace( 'http:'.strstr($siteurl, '//'), 'https:'.strstr($siteurl, '//'), $content);
     $content = str_replace( 'http:'.strstr($upload_dir['baseurl'], '//'), 'https:'.strstr($upload_dir['baseurl'], '//'), $content);
    
    $images =getPostImages($content, $post_id); 
    $_data['post_thumbnail_image']=$images['post_thumbnail_image'];
    $_data['content_first_image']=$images['content_first_image'];
    $_data['post_medium_image_300']=$images['post_medium_image_300'];
    $_data['post_thumbnail_image_624']=$images['post_thumbnail_image_624'];

    $_data['post_frist_image']=$images['post_frist_image']; 
    $_data['post_medium_image']=$images['post_medium_image'];
    $_data['post_large_image']=$images['post_large_image'];
    $_data['post_full_image']=$images['post_full_image'];
    $_data['post_all_images']=$images['post_all_images'];

     //获取广告参数

  $videoAdId=empty(get_option('wf_video_ad_id'))?'':get_option('wf_video_ad_id');
  $_data['videoAdId']=$videoAdId;
  
  $listAdId=empty(get_option('wf_list_ad_id'))?'':get_option('wf_list_ad_id');
  $listAd=empty(get_option('wf_list_ad'))?'0':"1"; 
  $listAdEvery=empty(get_option('wf_list_ad_every'))?5:(int)get_option('wf_list_ad_every');

  

  $_data['listAd']=$listAd;
  $_data['listAdId']=$listAdId;
  $_data['listAdEvery']=$listAdEvery;

    $comments_count = wp_count_comments($post_id);    
    $_data['total_comments']=$comments_count->approved;
    $category =get_the_category($post_id);
    if(!empty($category))
    {
      $_data['category_name'] =$category[0]->cat_name; 
    }
    
    $post_date =$post->post_date;
    //$_data['date'] =time_tran($post_date);
    $_data['post_date'] =time_tran($post_date);   
    // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
    // phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
    $like_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(1) FROM ".$wpdb->postmeta." where meta_value='like' and post_id=%d",$post_id));
    // phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
    // phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
    $_data['like_count']= $like_count; 
    $post_views = (int)get_post_meta($post_id, 'wl_pageviews', true);     
    $params = $request->get_params();
     if ( isset( $params['id'] ) ) {

      $praiseWord=get_option('wf_praise_word'); 
      $praiseWord=empty($praiseWord)?'鼓励':$praiseWord;
      $_data['praiseWord']=$praiseWord;

      $copyright_state=empty(get_option('wf_copyright_state'))?'':get_option('wf_copyright_state'); 
      $_data['copyright_state']=$copyright_state;
      

      //获取广告参数
      $detailAdId=empty(get_option('wf_detail_ad_id'))?'':get_option('wf_detail_ad_id');
      $detailAd=empty(get_option('wf_detail_ad'))?'0':"1";

      $rewardedVideoAdId=empty(get_option('wf_excitation_ad_id'))?'':get_option('wf_excitation_ad_id');
      $excitationAd = empty(get_post_meta($post_id, '_excitation', true))?"0":get_post_meta($post_id, '_excitation', true);

      $_data['excitationAd']=$excitationAd;
      $_data['rewardedVideoAdId']=$rewardedVideoAdId;

      $_data['detailAdId']=$detailAdId;
      $_data['detailAd']=$detailAd;
      
      $enterpriseMinapp=get_option('wf_enterprise_minapp'); 
      $enterpriseMinapp=empty($enterpriseMinapp)?'0':$enterpriseMinapp;
      
      
      $_data['enterpriseMinapp']=$enterpriseMinapp;
        $vcontent =get_post_qq_video($content);//解析腾讯视频
        if(!empty($vcontent))
        {
           $content=$vcontent;
        }

        //解析音频
        $audios=  get_post_content_audio($post->post_content);
        $_data['audios']=$audios;  
        // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
        // phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
        $postContent = $wpdb->get_var($wpdb->prepare("select post_content from ".$wpdb->posts." where id=%d",$post_id));
        // phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
        // phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
        if(has_shortcode($postContent, 'gallery' ))//处理内容里的相册显示
        {
          $content= get_content_gallery($postContent,true);
        }
        $_content['rendered'] =$content;
        $_content['raw'] =$raw;//古腾堡编辑器需要该属性，否则报错
        $_content['protected'] =$content_protected;  
        $_data['content']= $_content;


        $postImageUrl=get_option("wf_poster_imageurl");
        $_data['postImageUrl']= $postImageUrl;



        // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
        // phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
        $likes = $wpdb->get_results($wpdb->prepare("SELECT meta_key , (SELECT id from ".$wpdb->users." WHERE user_login=substring(meta_key,2)) as id ,(SELECT display_name from ".$wpdb->users." WHERE user_login=substring(meta_key,2)) as display_name  FROM ".$wpdb->postmeta." where meta_value='like' and post_id=%d",$post_id));
        // phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
        // phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery
        $avatarurls =array();
        foreach ($likes as $like) {
            $userId = $like->id;
            $display_name=$like->display_name;
            $pos=stripos($display_name,'wx.qlogo.cn');
            if($pos)
            {

              $avatar =$display_name;

            }
            else
            {
              $avatar= get_user_meta( $userId, 'avatar', true );
            }
            
            if(!empty($avatar))
            {
              $_avatarurl['avatarurl']  =$avatar;
             

            }
            else{
              $avatar = plugins_url()."/".REST_API_TO_MINIPROGRAM_PLUGIN_NAME."/includes/images/gravatar.png";
              $_avatarurl['avatarurl']  =$avatar;
            }
            $avatarurls[] = $_avatarurl; 
                   
        }
      $post_views =$post_views+1;  
      if(!update_post_meta($post_id, 'wl_pageviews', $post_views))   
      {  
        add_post_meta($post_id, 'wl_pageviews', 1, true);  
      } 
      $_data['avatarurls']= $avatarurls;
      //phpcs:disable WordPress.DateTime.RestrictedFunctions.timezone_change_date_default_timezone_set
      date_default_timezone_set('Asia/Shanghai');
      //phpcs:enable WordPress.DateTime.RestrictedFunctions.timezone_change_date_default_timezone_set
      $fristday= gmdate("Y-m-d H:i:s", strtotime("-1 year"));
      $today = gmdate("Y-m-d H:i:s"); //获取今天日期时间
        if(!empty($_data["tags"]))
        {
          $tags= $_data["tags"];
        }
     
        if(!empty($tags))
        {
          $tags=implode(",",$tags);
          // $sql="
          // SELECT distinct ID, post_title
          // FROM ".$wpdb->posts." , ".$wpdb->term_relationships.", ".$wpdb->term_taxonomy."
          // WHERE ".$wpdb->term_taxonomy.".term_taxonomy_id =  ".$wpdb->term_relationships.".term_taxonomy_id
          // AND ID = object_id
          // AND taxonomy = 'post_tag'
          // AND post_status = 'publish'
          // AND post_type = 'post'
          // AND term_id IN (" . $tags . ")
          // AND ID != '" . $post_id . "'
          // AND post_date BETWEEN '".$fristday."' AND '".$today."' 
          // ORDER BY  RAND()
          // LIMIT 5";
          // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery
          // phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching
          $related_posts = $wpdb->get_results($wpdb->prepare("
          SELECT distinct ID, post_title
          FROM ".$wpdb->posts." , ".$wpdb->term_relationships.", ".$wpdb->term_taxonomy."
          WHERE ".$wpdb->term_taxonomy.".term_taxonomy_id =  ".$wpdb->term_relationships.".term_taxonomy_id
          AND ID = object_id
          AND taxonomy = 'post_tag'
          AND post_status = 'publish'
          AND post_type = 'post'
          AND term_id IN (%s)
          AND ID != %s
          AND post_date BETWEEN %s AND %s
          ORDER BY  RAND()
          LIMIT 5",$tags,$post_id,$fristday,$today));
          // phpcs:enable WordPress.DB.DirectDatabaseQuery.NoCaching
          // phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery

          $_data['related_posts'] = $related_posts;

        }
        else{
          $_data['related_posts']=null;
        }
        
        
    }
    else 
    {
        unset($_data['content'] );   
        
    }
    $pageviews =$post_views ;   
    $_data['pageviews'] = $pageviews;
    if(!empty($category))
    {

      $category_id=$category[0]->term_id;
      $next_post = get_next_post($category_id, '', 'category');
      $previous_post = get_previous_post($category_id, '', 'category');
      $_data['next_post_id'] = !empty($next_post->ID)?$next_post->ID:null;
      $_data['next_post_title'] = !empty($next_post->post_title)?$next_post->post_title:null;
      $_data['previous_post_id'] = !empty($previous_post->ID)?$previous_post->ID:null;
      $_data['previous_post_title'] = !empty($previous_post->post_title)?$previous_post->post_title:null;

    }   
    $data->data = $_data;     
    return $data; 
}


//获取文章浏览次数
  function ram_post_views($before = '(点击 ', $after = ' 次)', $echo = 1)  
  {  
    global $post;  
    $post_id = $post->ID;  
    $views = (int)get_post_meta($post_id, 'wl_pageviews', true);  
    if ($echo) echo esc_html($before), number_format($views), esc_html($after);  

    else return $views;  
  } 


//增加或更新文章浏览次数
  function addPostPageviews()  
  {  
    if (is_singular())   
    {  
      global $post;  
      $post_id = $post->ID;  
      if($post_id)   
      {  
        $post_views = (int)get_post_meta($post_id, 'wl_pageviews', true);  
        if(!update_post_meta($post_id, 'wl_pageviews', ($post_views+1)))   
        {  
          add_post_meta($post_id, 'wl_pageviews', 1, true);  
        }  
      }  
    }  
  } 



