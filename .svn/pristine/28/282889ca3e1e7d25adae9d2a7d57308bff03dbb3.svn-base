<?php

function custom_comment_fields( $data, $comment, $request) {
    global $wpdb;
    $_data = $data->data;  
    $comment_id =$comment->comment_ID;
    $sql  =$wpdb->prepare("SELECT t2.comment_author as parent_name,t2.comment_date  as parent_date ,t1.user_id as user_id,(SELECT t3.meta_value  from ".$wpdb->commentmeta."  t3 where  t1.comment_ID = t3.comment_id  AND t3.meta_key = 'formId')  AS formId  from  ".$wpdb->comments." t1 LEFT JOIN ".$wpdb->comments." t2 on t1.comment_parent=t2.comment_ID  WHERE t1.comment_ID=%d",$comment_id);    
    $comment = $wpdb->get_row($sql);
    $userid=$comment->user_id;
    $parent_name=$comment->parent_name;
    $parent_date=$comment->parent_date;
    $formId=$comment->formId;
    if(empty($formId))
    {
        $formId="";
    }

    if(empty($parent_name))
    {
        $parent_name="";
    }

    if(empty($parent_date))
        {
            $parent_date="";
    }
    
    $_data['parent_name']=$parent_name; 
    $_data['parent_date']=$parent_date;  
    $_data['userid']=$userid;
    $_data['formId']=$formId;
    $data->data = $_data;
    return $data; 
}


