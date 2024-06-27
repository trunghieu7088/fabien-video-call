<?php
//get the services by custom condition

function get_all_video_services($page_number,$search_string='',$service_category='',$date_sort='',$price_sort='',$meettype_sort='')
{
    global $post;
    $number_service_each_pages=carbon_get_theme_option('custom_number_of_service_each_page');
    $service_args=array('post_type' => 'videoservice',    
    'posts_per_page' => $number_service_each_pages, 
    'paged'  =>$page_number,
    'post_status' =>'publish',               
    );

    if(empty($date_sort) || $date_sort=='latest')
    {
        $service_args['order']='DESC';
        $service_args['orderby']='date'; 
    }

    if($date_sort=='oldest')
    {
        $service_args['order']='ASC';
        $service_args['orderby']='date'; 
    }
    
    if($price_sort=='hightolow')
    {
        $service_args['meta_key']='video_service_price'; 
        $service_args['order']='DESC';
        $service_args['orderby']='meta_value_num';    
    }
    if($price_sort=='lowtohigh')
    {
        $service_args['meta_key']='video_service_price'; 
        $service_args['order']='ASC';
        $service_args['orderby']='meta_value_num';    
    }

    if(!empty($meettype_sort))
    {
        $service_args['meta_query'][]=array(
            'key'     => 'meeting_type',
            'value'   => $meettype_sort,          
            'compare' => '=',   
        );
    }

    if(!empty($search_string))
    {
        $service_args['s']=sanitize_text_field($search_string);  
    }

    if(!empty($service_category))
    {
        $service_args['tax_query'][]= array(
            'taxonomy' => 'service_category', // Replace with the actual taxonomy slug
            'field'    => 'slug', // You can use 'term_id', 'name', or 'slug' here
            'terms'    => $service_category, // Replace with the term you want to query
    );
    }    

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
    $converted_service['service_single_link']=$service->guid;
    
    $attached_image=get_attached_media('image',$service->ID);
    if($attached_image)
    {
        $final_attached=reset($attached_image);
        $converted_service['attached_image_url']=wp_get_attachment_image_url($final_attached->ID,'thumbnail');
       
    }   
    else
    {
        $converted_service['attached_image_url']=carbon_get_theme_option('custom_video_call_default_cover_img_service');
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
    $converted_service['service_owner_ID']= $service_owner->ID;
    $avatar=get_avatar_url($service_owner->ID, ['size' => '40']);

    $converted_service['avatar']=$avatar;
    $converted_service['currency_code']=get_post_meta($service->ID,'video_service_price_currency_code',true);
    $converted_service['currency_sign']=get_post_meta($service->ID,'video_service_price_currency_sign',true);

    $converted_service['meeting_type']='';
    $meeting_type=get_post_meta($service->ID,'meeting_type',true);

    if($meeting_type=='face-to-face')
    {
        $converted_service['meeting_type']='face-to-face';
    }
    if($meeting_type=='online-meeting')
    {
        $converted_service['meeting_type']='Online meeting';
    }



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


//add_action('init','testmet');

function testmet()
{
  //  $user_id = intval($atts['id']);
    $user = um_fetch_user( get_current_user_id() );
    $name = um_user( 'display_name' );
    $avatar = um_user( 'profile_photo', 80 );
    $url = um_user_profile_url( um_user( 'ID' ) );

    echo $name;
    echo $avatar;
    echo $url;
}