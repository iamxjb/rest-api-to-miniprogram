<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class RAM_REST_Posts_Controller  extends WP_REST_Controller{

    public function __construct() {
        
        $this->namespace     = 'watch-life-net/v1';
        $this->resource_name = 'post';
    }

    // Register our routes.
    public function register_routes() {
        register_rest_route( $this->namespace, '/' . $this->resource_name.'/swipe', array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'getPostSwipe' ),
                'permission_callback' => array( $this, 'get_item_permissions_check' )
                 
            ),
            // Register our schema callback.
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );

        register_rest_route( $this->namespace, '/' . $this->resource_name.'/like', array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'POST',
                'callback'  => array( $this, 'postLike' ),
                'permission_callback' => array( $this, 'post_like_permissions_check' ),
                'args'               => array(              
                    'postid' => array(
                        'required' => true
                    ),                    
                    'openid' => array(
                        'required' => true
                    )
                   
                )
                 
            ),
            // Register our schema callback.
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );

        register_rest_route( $this->namespace, '/' . $this->resource_name.'/islike', array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'POST',
                'callback'  => array( $this, 'getIsLike' ),
                'permission_callback' => array( $this, 'post_like_permissions_check' ),
                'args'               => array(              
                    'postid' => array(
                        'required' => true
                    ),                    
                    'openid' => array(
                        'required' => true
                    )
                   
                )
                 
            ),
            // Register our schema callback.
            'schema' => array( $this, 'get_public_item_schema' ),
        ) ); 

        register_rest_route( $this->namespace, '/' . $this->resource_name.'/mylike', array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'getmyLike' ),
                'permission_callback' => array( $this, 'get_mylike_permissions_check' ),
                'args'               => array( 
                    'openid' => array(
                        'required' => true
                    )
                   
                )
                 
            ),
            // Register our schema callback.
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );

        register_rest_route( $this->namespace, '/' . $this->resource_name.'/hotpostthisyear', array(
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'getTopHotPostsThisYear' ),
                'permission_callback' => array( $this, 'get_item_permissions_check' )
                 
            ),
            // Register our schema callback.
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );

        register_rest_route( $this->namespace, '/' . $this->resource_name.'/hotpost', array(
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'getTopHotPosts' ),
                'permission_callback' => array( $this, 'get_item_permissions_check' )
                 
            ),
            // Register our schema callback.
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );

        register_rest_route( $this->namespace, '/' . $this->resource_name.'/likethisyear', array(
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'getTopLikePostsThisYear' ),
                'permission_callback' => array( $this, 'get_item_permissions_check' )
                 
            ),
            // Register our schema callback.
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );

        register_rest_route( $this->namespace, '/' . $this->resource_name.'/pageviewsthisyear', array(
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'getTopPageviewsPostsThisYear' ),
                'permission_callback' => array( $this, 'get_item_permissions_check' )
                 
            ),
            // Register our schema callback.
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );

        register_rest_route( $this->namespace, '/' . $this->resource_name.'/praisethisyear', array(
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'getTopPageviewsPostsThisYear' ),
                'permission_callback' => array( $this, 'get_item_permissions_check' )
                 
            ),
            // Register our schema callback.
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );

        register_rest_route( $this->namespace, '/' . $this->resource_name.'/praise', array(
            array(
                'methods'   => 'POST',
                'callback'  => array( $this, 'postPraise' ),
                'permission_callback' => array( $this, 'get_praise_permissions_check' ),
                'args'               => array( 
                    'openid' => array(
                        'required' => true
                    ),
                    'orderid' => array(
                        'required' => true
                    ),
                    'postid' => array(
                        'required' => true
                    ),
                    'money' => array(
                        'required' => true
                    )
                   
                )
                 
            ),
            // Register our schema callback.
            'schema' => array( $this, 'get_public_item_schema' ),
        ) ); 
        register_rest_route( $this->namespace, '/' . $this->resource_name.'/mypraise', array(
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'getmypraise' ),
                'permission_callback' => array( $this, 'get_mypraise_permissions_check' ),
                'args'               => array( 
                    'openid' => array(
                        'required' => true
                    )
                )
                 
            ),
            // Register our schema callback.
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );
        register_rest_route( $this->namespace, '/' . $this->resource_name.'/allpraise', array(
            array(
                'methods'   => 'GET',
                'callback'  => array( $this, 'getallpraise' ),
                'permission_callback' => array( $this, 'get_item_permissions_check' )
                 
            ),
            // Register our schema callback.
            'schema' => array( $this, 'get_public_item_schema' ),
        ) );                  
    }

    function getallpraise ($request)
    {
        global $wpdb;    

        $sql="SELECT ".$wpdb->users.".display_name as avatarurl  from(SELECT substring(substring_index(".$wpdb->postmeta.".meta_key,'@',1),2) as openid,".$wpdb->postmeta.".meta_id from ".$wpdb->postmeta." where ".$wpdb->postmeta.".meta_value like '%praise' )t1  LEFT JOIN ".$wpdb->users." ON ".$wpdb->users.".user_login = t1.openid  ORDER by t1.meta_id desc";
              
            $avatarurls = $wpdb->get_results($sql);
            
           

            if(!empty($avatarurls))
            {
                $result["code"]="success";
                $result["message"]= "获取赞赏成功";
                $result["status"]="200";
                $result["avatarurls"]=$avatarurls;   
            }
            else
            {
                $result["code"]="success";
                $result["message"]= "获取赞赏失败";
                $result["status"]="500";
            }                
            $response = rest_ensure_response($result);
            return $response; 
    }

    function getmypraise($request)
    {
        global $wpdb;
        $openid= $request['openid'];
        $sql ="SELECT * from ".$wpdb->posts."  where  post_type='post' and ID in  
    (SELECT post_id from ".$wpdb->postmeta." where meta_value like '%praise' and meta_key like'%".$openid."%') ORDER BY post_date desc LIMIT 20";        
            $_posts = $wpdb->get_results($sql);
            $posts =array();
            foreach ($_posts as $post) {
                
                $_data["post_id"]  =$post->ID;
                $_data["post_title"]  =$post->post_title;
                $posts[]=$_data;
            }

            $result["code"]="success";
            $result["message"]= "get  my praise success";
            $result["status"]="200";
            $result["data"]=$posts;                   
            $response = rest_ensure_response($result);
            return $response;


    }
    function postPraise($request) {

        $openid= $request['openid'];       
        $orderid=$request['orderid'];
        $postid =$request['postid'];
        $money =$request['money'];

        $openid="_".$openid;
        $orderid=$orderid;
        $meta_key=$openid."@"."$orderid";
        $meta_value=$money."_praise";
        if(update_post_meta($postid, $meta_key,$meta_value,true))
        {
            $result["code"]="success";
            $result["message"]= "赞赏成功";
            $result["status"]="200";  
        }
        else
        {
            $result["code"]="success";
            $result["message"]= "赞赏失败";
            $result["status"]="500";  
        }

        $response = rest_ensure_response($result);
        return $response; 
    }


    function getTopPraisePostsThisYear($request)
    {
        $limit=10;
        global $wpdb, $post, $tableposts, $tablecomments, $time_difference, $post;
        date_default_timezone_set('Asia/Shanghai');
        $today = date("Y-m-d H:i:s"); //获取今天日期时间   
       // $fristday = date( "Y-m-d H:i:s",  strtotime(date("Y",time())."-1"."-1"));  //本年第一天;
        $fristday= date("Y-m-d H:i:s", strtotime("-1 year"));  
        $sql="SELECT  ".$wpdb->posts.".ID as ID, post_title, post_name,post_content,post_date, count(".$wpdb->postmeta.".post_id) AS 'praise_total' FROM ".$wpdb->posts." LEFT JOIN ".$wpdb->postmeta." ON ".$wpdb->posts.".ID = ".$wpdb->postmeta.".post_id WHERE ".$wpdb->postmeta.".meta_value like '%praise' AND post_date BETWEEN '".$fristday."' AND '".$today."' AND post_status = 'publish' and  post_type='post' AND post_password = '' GROUP BY ".$wpdb->postmeta.".post_id ORDER  BY praise_total DESC LIMIT ". $limit;
        $mostlikes = $wpdb->get_results($sql);
        $posts =array();
        foreach ($mostlikes as $post) {
        
                $post_id = (int) $post->ID;
                $post_title = stripslashes($post->post_title);
                $pageviews=0;
                if(!empty($post->pageviews_total))
                {
                    $pageviews = (int) $post->pageviews_total;
                }
                
                $post_date =$post->post_date;
                $post_permalink = get_permalink($post->ID);            
                $_data["post_id"]  =$post_id;
                $_data["post_title"] =$post_title; 
                //$_data["pageviews"] =$pageviews;  
                $_data["post_date"] =$post_date; 
                $_data["post_permalink"] =$post_permalink;

                $pageviews = (int) get_post_meta( $post_id, 'wl_pageviews',true);
                $_data['pageviews'] = $pageviews;
                
                $like_count = $wpdb->get_var("SELECT COUNT(1) FROM ".$wpdb->postmeta." where meta_value='like' and post_id=".$post_id);
                $_data['like_count'] = $like_count;

                $comment_total = $wpdb->get_var("SELECT COUNT(1) FROM ".$wpdb->comments." where  comment_approved = '1' and comment_post_ID=".$post_id);
                $_data['comment_total']= $comment_total;

                $images =getPostImages($post->post_content,$post_id);         
                
                $_data['post_thumbnail_image']=$images['post_thumbnail_image'];
                $_data['content_first_image']=$images['content_first_image'];
                $_data['post_medium_image_300']=$images['post_medium_image_300'];
                $_data['post_thumbnail_image_624']=$images['post_thumbnail_image_624'];
                $_data['post_frist_image']=$images['post_frist_image'];
                $_data['post_medium_image']=$images['post_medium_image'];
                  $_data['post_large_image']=$images['post_large_image'];
                  $_data['post_full_image']=$images['post_full_image'];
                  $_data['post_all_images']=$images['post_all_images'];
                $posts[] = $_data;
                
                
        } 
        $response = rest_ensure_response($posts);
        return $response; 
    }

     function getTopPageviewsPostsThisYear($request)
     {
        $limit=10;
        global $wpdb, $post, $tableposts, $tablecomments, $time_difference, $post;
            date_default_timezone_set('Asia/Shanghai');
            $today = date("Y-m-d H:i:s"); //获取今天日期时间   
           // $fristday = date( "Y-m-d H:i:s",  strtotime(date("Y",time())."-1"."-1"));  //本年第一天;
            $fristday= date("Y-m-d H:i:s", strtotime("-1 year"));  
            $sql="SELECT  ".$wpdb->posts.".ID as ID, post_title, post_name,post_content,post_date, CONVERT(".$wpdb->postmeta.".meta_value,SIGNED) AS 'pageviews_total' FROM ".$wpdb->posts." LEFT JOIN ".$wpdb->postmeta." ON ".$wpdb->posts.".ID = ".$wpdb->postmeta.".post_id WHERE ".$wpdb->postmeta.".meta_key ='wl_pageviews' AND post_date BETWEEN '".$fristday."' AND '".$today."' AND post_status = 'publish' AND post_password = '' ORDER  BY pageviews_total DESC LIMIT ". $limit;
            $mostlikes = $wpdb->get_results($sql);
            $posts =array();
            foreach ($mostlikes as $post) {
            
                    $post_id = (int) $post->ID;
                    $post_title = stripslashes($post->post_title);
                    $pageviews = (int) $post->pageviews_total;
                    $post_date =$post->post_date;
                    $post_permalink = get_permalink($post->ID);            
                    $_data["post_id"]  =$post_id;
                    $_data["post_title"] =$post_title; 
                    $_data["pageviews"] =$pageviews;  
                    $_data["post_date"] =$post_date; 
                    $_data["post_permalink"] =$post_permalink;                    
                    
                    $like_count = $wpdb->get_var("SELECT COUNT(1) FROM ".$wpdb->postmeta." where meta_value='like' and post_id=".$post_id);
                    $_data['like_count'] = $like_count;

                    $comment_total = $wpdb->get_var("SELECT COUNT(1) FROM ".$wpdb->comments." where  comment_approved = '1' and comment_post_ID=".$post_id);
                    $_data['comment_total']= $comment_total;

                    $images =getPostImages($post->post_content,$post_id);         
                    
                    $_data['post_thumbnail_image']=$images['post_thumbnail_image'];
                    $_data['content_first_image']=$images['content_first_image'];
                    $_data['post_medium_image_300']=$images['post_medium_image_300'];
                    $_data['post_thumbnail_image_624']=$images['post_thumbnail_image_624'];
                    $_data['post_frist_image']=$images['post_frist_image'];
                    $_data['post_medium_image']=$images['post_medium_image'];
                      $_data['post_large_image']=$images['post_large_image'];
                      $_data['post_full_image']=$images['post_full_image'];
                      $_data['post_all_images']=$images['post_all_images'];
                    $posts[] = $_data;
                    
                    
            } 
         $response = rest_ensure_response($posts);
        return $response; 
     }
    function getTopLikePostsThisYear($request)
    {
        global $wpdb, $post, $tableposts, $tablecomments, $time_difference, $post;
        $limit=10;
        date_default_timezone_set('Asia/Shanghai');
        $today = date("Y-m-d H:i:s"); //获取今天日期时间   
       // $fristday = date( "Y-m-d H:i:s",  strtotime(date("Y",time())."-1"."-1"));  //本年第一天;
        $fristday= date("Y-m-d H:i:s", strtotime("-1 year"));  
        $sql="SELECT  ".$wpdb->posts.".ID as ID, post_title, post_name,post_content,post_date, COUNT(".$wpdb->postmeta.".post_id) AS 'like_total' FROM ".$wpdb->posts." LEFT JOIN ".$wpdb->postmeta." ON ".$wpdb->posts.".ID = ".$wpdb->postmeta.".post_id WHERE ".$wpdb->postmeta.".meta_value ='like' AND post_date BETWEEN '".$fristday."' AND '".$today."' AND post_status = 'publish' AND post_password = '' GROUP BY ".$wpdb->postmeta.".post_id ORDER  BY like_total DESC LIMIT ". $limit;
        $mostlikes = $wpdb->get_results($sql);
        $posts =array();
        foreach ($mostlikes as $post) {
        
                $post_id = (int) $post->ID;
                $post_title = stripslashes($post->post_title);
                $like_total = (int) $post->like_total;
                $post_date =$post->post_date;
                $post_permalink = get_permalink($post->ID);            
                $_data["post_id"]  =$post_id;
                $_data["post_title"] =$post_title; 
                $_data["like_count"] =$like_total;  
                $_data["post_date"] =$post_date; 
                $_data["post_permalink"] =$post_permalink;
                
                $pageviews = (int) get_post_meta( $post_id, 'wl_pageviews',true);
                $_data['pageviews'] = $pageviews;

                $comment_total = $wpdb->get_var("SELECT COUNT(1) FROM ".$wpdb->comments." where  comment_approved = '1' and comment_post_ID=".$post_id);
                $_data['comment_total']= $comment_total;

                $images =getPostImages($post->post_content,$post_id);         
                
                $_data['post_thumbnail_image']=$images['post_thumbnail_image'];
                $_data['content_first_image']=$images['content_first_image'];
                $_data['post_medium_image_300']=$images['post_medium_image_300'];
                $_data['post_thumbnail_image_624']=$images['post_thumbnail_image_624'];
                $_data['post_frist_image']=$images['post_frist_image'];
                $_data['post_medium_image']=$images['post_medium_image'];
                  $_data['post_large_image']=$images['post_large_image'];
                  $_data['post_full_image']=$images['post_full_image'];
                  $_data['post_all_images']=$images['post_all_images'];
                $posts[] = $_data;
                
                
        } 
        $response = rest_ensure_response($posts);
        return $response;  

    }

    function getTopHotPosts($request)
    {
        $limit=10;
        global $wpdb, $post, $tableposts, $tablecomments, $time_difference, $post;
        date_default_timezone_set('Asia/Shanghai');
        $sql="SELECT  ".$wpdb->posts.".ID as ID, post_title, post_name, post_content,post_date, COUNT(".$wpdb->comments.".comment_post_ID) AS 'comment_total' FROM ".$wpdb->posts." LEFT JOIN ".$wpdb->comments." ON ".$wpdb->posts.".ID = ".$wpdb->comments.".comment_post_ID WHERE comment_approved = '1' AND post_date < '".date("Y-m-d H:i:s", (time() + ($time_difference * 3600)))."' AND post_status = 'publish' AND post_password = '' GROUP BY ".$wpdb->comments.".comment_post_ID ORDER  BY comment_total DESC LIMIT ". $limit;
        $mostcommenteds = $wpdb->get_results($sql);
        $posts =array();  
        foreach ($mostcommenteds as $post) {
                $post_id = (int) $post->ID;
                $post_title = stripslashes($post->post_title);
                $comment_total = (int) $post->comment_total;
                $post_date =$post->post_date;
                $post_permalink = get_permalink($post->ID);            
                $_data["post_id"]  =$post_id;
                $_data["post_title"] =$post_title; 
                $_data["comment_total"] =$comment_total;  
                $_data["post_date"] =$post_date;
                $_data["post_permalink"] =$post_permalink;
                $pageviews = (int) get_post_meta( $post_id, 'wl_pageviews',true);
                $_data['pageviews'] = $pageviews;
                
                $like_count = $wpdb->get_var("SELECT COUNT(1) FROM ".$wpdb->postmeta." where meta_value='like' and post_id=".$post_id);
                $_data['like_count']= $like_count;


                $images =getPostImages($post->post_content,$post_id);         
                
                $_data['post_thumbnail_image']=$images['post_thumbnail_image'];
                $_data['content_first_image']=$images['content_first_image'];
                $_data['post_medium_image_300']=$images['post_medium_image_300'];
                $_data['post_thumbnail_image_624']=$images['post_thumbnail_image_624']; 
                $_data['post_frist_image']=$images['post_frist_image'];
                $_data['post_medium_image']=$images['post_medium_image'];
                  $_data['post_large_image']=$images['post_large_image'];
                  $_data['post_full_image']=$images['post_full_image'];
                  $_data['post_all_images']=$images['post_all_images'];         
                            
                $posts[] = $_data;    
                
        }
        $response = rest_ensure_response($posts);
        return $response;
     
    }

    function getTopHotPostsThisYear($request)
    {
        global $wpdb, $post, $tableposts, $tablecomments, $time_difference, $post;
        date_default_timezone_set('Asia/Shanghai');
        $limit = 10;
        $today = date("Y-m-d H:i:s"); //获取今天日期时间   
       // $fristday = date( "Y-m-d H:i:s",  strtotime(date("Y",time())."-1"."-1"));  //本年第一天;
        $fristday= date("Y-m-d H:i:s", strtotime("-1 year"));  
        $sql="SELECT  ".$wpdb->posts.".ID as ID, post_title, post_name,post_content,post_date, COUNT(".$wpdb->comments.".comment_post_ID) AS 'comment_total' FROM ".$wpdb->posts." LEFT JOIN ".$wpdb->comments." ON ".$wpdb->posts.".ID = ".$wpdb->comments.".comment_post_ID WHERE comment_approved = '1' AND post_date BETWEEN '".$fristday."' AND '".$today."' AND post_status = 'publish' AND post_password = '' GROUP BY ".$wpdb->comments.".comment_post_ID ORDER  BY comment_total DESC LIMIT ". $limit;
        $mostcommenteds = $wpdb->get_results($sql);
        $posts =array();
        foreach ($mostcommenteds as $post) {
        
                $post_id = (int) $post->ID;
                $post_title = stripslashes($post->post_title);
                $comment_total = (int) $post->comment_total;
                $post_date =$post->post_date;
                $post_permalink = get_permalink($post->ID);            
                $_data["post_id"]  =$post_id;
                $_data["post_title"] =$post_title; 
                $_data["comment_total"] =$comment_total;  
                $_data["post_date"] =$post_date; 
                $_data["post_permalink"] =$post_permalink;
                
                $pageviews = (int) get_post_meta( $post_id, 'wl_pageviews',true);
                $_data['pageviews'] = $pageviews;

                $like_count = $wpdb->get_var("SELECT COUNT(1) FROM ".$wpdb->postmeta." where meta_value='like' and post_id=".$post_id);
                $_data['like_count']= $like_count;

                $images =getPostImages($post->post_content,$post_id);         
                
                $_data['post_thumbnail_image']=$images['post_thumbnail_image'];
                $_data['content_first_image']=$images['content_first_image'];
                $_data['post_medium_image_300']=$images['post_medium_image_300'];
                $_data['post_thumbnail_image_624']=$images['post_thumbnail_image_624'];

                $_data['post_frist_image']=$images['post_frist_image'];
                  $_data['post_medium_image']=$images['post_medium_image'];
                  $_data['post_large_image']=$images['post_large_image'];
                  $_data['post_full_image']=$images['post_full_image'];
                  $_data['post_all_images']=$images['post_all_images'];
                $posts[] = $_data;
        }

        $response = rest_ensure_response($posts);
        return $response;  

    }
    function getPostSwipe($request) {    
    
        global $wpdb;
        $postSwipeIDs = get_option('wf_swipe');
        $posts =array();                  
        if(!empty($postSwipeIDs))
        {
            $sql="SELECT *  from ".$wpdb->posts." where id in(".$postSwipeIDs.")";
            $_posts = $wpdb->get_results($sql);
            
            foreach ($_posts as $post) {    
                $post_id = (int) $post->ID;
                $post_title = stripslashes($post->post_title);                
                $post_date =$post->post_date;
                $post_permalink = get_permalink($post->ID);            
                $_data["id"]  =$post_id;
                $_data["post_title"] =$post_title;
                $_data["post_date"] =$post_date; 
                $_data["post_permalink"] =$post_permalink;
                $_data['type']="detailpage";  
                
                $pageviews = (int) get_post_meta( $post_id, 'wl_pageviews',true);
                $_data['pageviews'] = $pageviews;

                $comment_total = $wpdb->get_var("SELECT COUNT(1) FROM ".$wpdb->comments." where  comment_approved = '1' and comment_post_ID=".$post_id);
                $_data['comment_total']= $comment_total;

                $images =getPostImages($post->post_content,$post_id);         
                
                $_data['post_thumbnail_image']=$images['post_thumbnail_image'];
                $_data['content_first_image']=$images['content_first_image'];
                $_data['post_medium_image_300']=$images['post_medium_image_300'];
                $_data['post_thumbnail_image_624']=$images['post_thumbnail_image_624'];

                $_data['post_frist_image']=$images['post_frist_image'];
                $_data['post_medium_image']=$images['post_medium_image'];
                $_data['post_large_image']=$images['post_large_image'];
                $_data['post_full_image']=$images['post_full_image'];
                $_data['post_all_images']=$images['post_all_images'];
                $posts[] = $_data;               
            }

            $result["code"]="success";
            $result["message"]= "获取轮播图成功";
            $result["status"]="200";
            $result["posts"]=$posts; 
        
        }
        else
        {
            return new WP_Error( 'error', '没有设置轮播图的文章id' , array( 'status' => "500" ) );
                             
            
        }

        $response = rest_ensure_response( $result);
        return $response;   
    

    }

    public function getmyLike($request)
    {
        global $wpdb;
        $openid= $request['openid'];
        $sql ="SELECT * from ".$wpdb->posts."  where ID in  
(SELECT post_id from ".$wpdb->postmeta." where meta_value='like' and meta_key='_".$openid."') ORDER BY post_date desc LIMIT 20";        
        $_posts = $wpdb->get_results($sql);
        $posts =array();
        foreach ($_posts as $post) {
            
            $_data["post_id"]  =$post->ID;
            $_data["post_title"]  =$post->post_title;
            $posts[]=$_data;
        }

        $result["code"]="success";
        $result["message"]= "获取我点赞的文章成功";
        $result["status"]="200";
        $result["data"]=$posts; 

        $response = rest_ensure_response( $result);
        return $response;         

    }

    public function getIsLike($openid,$postid) {
        $openid= $request['openid'];
        $postid=$request['postid'];    
        $openid="_".$openid; 
        $postmeta = get_post_meta($postid, $openid,true);
        if (!empty($postmeta))
        {
            
                $result["code"]="success";
                $result["message"]= "you have  posted like ";
                $result["status"]="200";                   
                
            
            
        }
        else
        {
                $result["code"]="success";
                $result["message"]= "you have not  posted like ";
                $result["status"]="501";                   
                
            
        }

        $response = rest_ensure_response( $result);
        return $response;  

    
    }

    public function postLike($request)
    {
        $openid= $request['openid'];
        $openid="_".$openid;
        $postid=$request['postid'];
        $postmeta = get_post_meta($postid, $openid,true);
        if (empty($postmeta))
        {
            
            if(add_post_meta($postid, $openid,'like', true))
            {
                $result["code"]="success";
                $result["message"]= "点赞成功 ";
                $result["status"]="200";    
                
            
            }
            else
            {
                return new WP_Error( 'error', '点赞失败' , array( 'status' => "500" ) );
            }            
            
            
        }
        else
        {
                $result["code"]="success";
                $result["message"]= "已点赞 ";
                $result["status"]="501";                   
                
            
        }

        $response = rest_ensure_response( $result);
        return $response;
    }


    public function post_like_permissions_check($request ){
      

        $openid= $request['openid'];
        $postid=$request['postid'];

        if(empty($openid) || empty($postid) )
        {
            return new WP_Error( 'error', '参数错误', array( 'status' => 400 ) );
        }               
        else
        { 
            if(!username_exists($openid))
            {
                return new WP_Error( 'error', '不允许提交', array( 'status' => 400 ) );
            }
             if(is_wp_error(get_post($postid)))
            {
                 return new WP_Error( 'error', 'postId参数错误', array( 'status' => 400 ) );
            }
        }

        return true;
        
        
    }
    public function get_mylike_permissions_check ($request)
    {
        $openid= $request['openid']; 
        if(empty($openid))
        {
            return new WP_Error( 'error', 'openid is empty', array( 'status' => 500 ) );
        }
        
        else
        { 
            if(!username_exists($openid))
            {
                return new WP_Error( 'error', '不允许提交', array( 'status' => 500 ) );
            }  
        
        }

        return true;
    }
    public function get_item_permissions_check($request ) {      
        
        return true;
    }

    public function get_praise_permissions_check($request)
    {
        $openid= $request['openid'];       
        $orderid=$request['orderid'];
        $postid =$request['postid'];
        $money =$request['money'];
        if(empty($openid) || empty($orderid) || empty($money) || empty($postid) )
        {
            return new WP_Error( 'error', '参数错误', array( 'status' => 500 ) );
        }
        else if(get_post($postid)==null)
        {
             return new WP_Error( 'error', 'postId参数错误', array( 'status' => 500 ) );
        }
        
        else
        { 
            if(!username_exists($openid))
            {
                return new WP_Error( 'error', '不允许提交', array('status' => 500 ));
            }
            else if(is_wp_error(get_post($postid)))
            {
                 return new WP_Error( 'error', 'postId参数错误', array( 'status' => 500 ) );
            }
            
        
        }

        return true;

    }

    public function get_mypraise_permissions_check($request)
    {

        $openid= $request['openid'];   

        if(empty($openid))
        {
            return new WP_Error( 'error', '参数错误', array( 'status' => 500 ) );
        }
        
        else
        { 
            if(!username_exists($openid))
            {
                return new WP_Error( 'error', '不允许提交', array( 'status' => 500 ) );
            } 
        }

        return true;

    }



   
  
}
