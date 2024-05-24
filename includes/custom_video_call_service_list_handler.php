<?php
//get the services by custom condition

function get_all_video_services($page_number)
{
    global $post;
    $service_args=array('post_type' => 'videoservice',
    'posts_per_page' => 10, 
    'paged'  =>$page_number,
    'post_status' =>'publish',            
   /* 'meta_query'     => array(
        //turn on this later
      //  'relation' => 'AND', 
        array(
            'key'   => 'custom_role',
            'value' => 'expert',
        ),        
        array(
            'key'   => 'is_custom_public',
            'value' => 'true',
        ),
    ),     */      
    );

    $service_args['order']='DESC';
    $service_args['orderby']='date'; 

    $service_query=new WP_Query($service_args);

    $all_services=array();
    $all_services_info=array();
    if($service_query->have_posts())
    {
        while($service_query->have_posts())
        {
            $service_query->the_post();
            $converted_service=convert_service_for_display($post);
            $all_services[]=$converted_service;
        }
    }
    $all_services_info['service_list']= $all_services;
    $all_services_info['max_num_pages']= $service_query->max_num_pages;
    wp_reset_postdata();  

    return $all_services_info;
}

function convert_service_for_display($service)
{   

    $service_owner=get_user_by('ID',$service->post_author);
    $converted_service['ID']=$service->ID;

    $converted_service['title']=$service->post_title;
    $converted_service['description']=$service->post_content;
    $converted_service['service_slug']=$service->post_name;
    
    $attached_image=get_attached_media('image',$service->ID);
    if($attached_image)
    {
        $final_attached=reset($attached_image);
        $converted_service['attached_image_url']=wp_get_attachment_image_url($final_attached->ID,'thumbnail');
       
    }   
    else
    {
        $converted_service['attached_image_url']='http://fabien.et/wp-content/uploads/2024/05/deer.png';
    }

    $duration=get_post_meta($service->ID,'video_service_duration',true);

    if(!$duration)
    {
        $duration=0;
    }
    $converted_service['duration']= $duration;

    $price=get_post_meta($service->ID,'video_service_price',true);
    if(!$price)
    {
        $price=0;
    }
    $converted_service['price']=$price;

    $service_category=wp_get_post_terms($service->ID,'service_category');
   
    $converted_service['category']= $service_category[0]->name;
    $converted_service['category_id']= $service_category[0]->term_id;

    $converted_service['human_readable_time']=timeAgo($service->post_date);

    //display name--> maybe change to match with plugin UM
    $converted_service['service_owner_name']='Posted by '.$service_owner->display_name;
    $converted_service['service_owner_url']= get_author_posts_url($service_owner->ID);
    $avatar=get_avatar_url($service_owner->ID, ['size' => '40']);

    $converted_service['avatar']=$avatar;

    return $converted_service;
}

//handling human readable time

function timeAgo($datetime) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->y > 0) {
        return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
    } elseif ($diff->m > 0) {
        return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
    } elseif ($diff->d > 0) {
        return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
    } elseif ($diff->h > 0) {
        return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    } else {
        return 'just now';
    }
}

//add_action('init','testcode',999);

function testcode()
{
    $custom=get_post(417);
    $image=get_attached_media('image',$custom);
    
    echo '<pre>';
    print_r($image);

    echo '</pre>';
    echo $image[416]->post_author;
}

