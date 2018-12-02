<?php 
//禁止直接访问
if ( ! defined( 'ABSPATH' ) ) exit;

function custom_post_fields( $data, $post, $request) { 
    global $wpdb;
    $_data = $data->data; 
    $post_id =$post->ID;

    $content =get_the_content();
     
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

    $comments_count = wp_count_comments($post_id);    
    $_data['total_comments']=$comments_count->total_comments;
    $category =get_the_category($post_id);
    $_data['category_name'] =$category[0]->cat_name; 
    $post_date =$post->post_date;
    $_data['date'] =time_tran($post_date);
    $sql =$wpdb->prepare("SELECT COUNT(1) FROM ".$wpdb->postmeta." where meta_value='like' and post_id=%d",$post_id);
    $like_count = $wpdb->get_var($sql);
    $_data['like_count']= $like_count; 
    $post_views = (int)get_post_meta($post_id, 'wl_pageviews', true);     
    $params = $request->get_params();
     if ( isset( $params['id'] ) ) {

        $sql=$wpdb->prepare("SELECT meta_key , (SELECT display_name from ".$wpdb->users." WHERE user_login=substring(meta_key,2)) as avatarurl FROM ".$wpdb->postmeta." where meta_value='like' and post_id=%d",$post_id);
        $likes = $wpdb->get_results($sql);
        $avatarurls =array();
        foreach ($likes as $like) {
            $_avatarurl['avatarurl']  =$like->avatarurl;   
            $avatarurls[] = $_avatarurl;        
        }
      $post_views =$post_views+1;  
      if(!update_post_meta($post_id, 'wl_pageviews', $post_views))   
      {  
        add_post_meta($post_id, 'wl_pageviews', 1, true);  
      } 
      $_data['avatarurls']= $avatarurls;


      date_default_timezone_set('Asia/Shanghai');
      $fristday= date("Y-m-d H:i:s", strtotime("-5 year"));
      $today = date("Y-m-d H:i:s"); //获取今天日期时间
      $tags= $_data["tags"];
        if(count($tags)>0)
        {
          $tags=implode(",",$tags);
          $sql="
          SELECT DISTINCT ID, post_title
          FROM ".$wpdb->posts." , ".$wpdb->term_relationships.", ".$wpdb->term_taxonomy."
          WHERE ".$wpdb->term_taxonomy.".term_taxonomy_id =  ".$wpdb->term_relationships.".term_taxonomy_id
          AND ID = object_id
          AND taxonomy = 'post_tag'
          AND post_status = 'publish'
          AND post_type = 'post'
          AND term_id IN (" . $tags . ")
          AND ID != '" . $post_id . "'
          AND post_date BETWEEN '".$fristday."' AND '".$today."' 
          ORDER BY  RAND()
          LIMIT 5";
          $related_posts = $wpdb->get_results($sql);

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
    $category_id=$category[0]->term_id;
    $next_post = get_next_post($category_id, '', 'category');
    $previous_post = get_previous_post($category_id, '', 'category');
    $_data['next_post_id'] = !empty($next_post->ID)?$next_post->ID:null;
    $_data['next_post_title'] = !empty($next_post->post_title)?$next_post->post_title:null;
    $_data['previous_post_id'] = !empty($previous_post->ID)?$previous_post->ID:null;
    $_data['previous_post_title'] = !empty($previous_post->post_title)?$previous_post->post_title:null;
    unset($_data['format']);
    unset($_data['ping_status']);
    unset($_data['template']);
    unset($_data['type']);
    //unset($_data['slug']);
    unset($_data['modified_gmt']);
    unset($_data['date_gmt']);
    unset($_data['meta']);
    unset($_data['guid']);
    unset($_data['curies']);
    unset($_data['modified']);
    unset($_data['status']);
    unset($_data['comment_status']);
    unset($_data['sticky']);
    unset($_data['author']);       
    $data->data = $_data;     
    return $data; 
}


//获取文章浏览次数
  function ram_post_views($before = '(点击 ', $after = ' 次)', $echo = 1)  
  {  
    global $post;  
    $post_id = $post->ID;  
    $views = (int)get_post_meta($post_id, 'wl_pageviews', true);  
    if ($echo) echo $before, number_format($views), $after;  
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



