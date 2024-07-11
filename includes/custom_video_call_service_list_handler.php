<?php
//get the services by custom condition

function get_all_video_services($page_number,$search_string='',$service_category='',$date_sort='',$price_sort='',$meettype_sort='',$only_myservice='no')
{
    global $post;
    $number_service_each_pages=carbon_get_theme_option('custom_number_of_service_each_page');
    $service_args=array('post_type' => 'videoservice',    
    'posts_per_page' => $number_service_each_pages, 
    'paged'  =>$page_number,
    'post_status' =>'publish',               
    );

    if($only_myservice=='yes')
    {
        $service_args['author']=get_current_user_id();
        //only the service owner can see their unpublished services
        $service_args['post_status']=array('publish','pending','unpublished');
    }

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

    $textManager = TextManager::getInstance();
    $service_owner=get_user_by('ID',$service->post_author);
    $converted_service['ID']=$service->ID;    
    
    //initialize UM info
    $custom_um_user = um_fetch_user( $service_owner->ID );


    $converted_service['title']=$service->post_title;
    $converted_service['description']=$service->post_content;
    $converted_service['short_description']=wp_trim_words($service->post_content,35,'...');
    $converted_service['service_slug']=$service->post_name;
    $converted_service['service_single_link']=$service->guid;
    $converted_service['service_status']=$service->post_status;
    
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
    $converted_service['service_owner_name']=$textManager->getText('posted_by_label').' '.um_user( 'display_name' );
    $avatar=um_get_user_avatar_url( $service_owner->ID, '80' );
    $converted_service['avatar']=$avatar;   
    //$converted_service['service_owner_url']= um_user_profile_url( $service_owner->ID);
    $converted_service['service_owner_url']=site_url('/profil/').get_user_meta($service_owner->ID,'um_user_profile_url_slug_user_login',true);    
    $converted_service['service_owner_ID']= $service_owner->ID;
   
   
    $converted_service['currency_code']=get_post_meta($service->ID,'video_service_price_currency_code',true);
    $converted_service['currency_sign']=get_post_meta($service->ID,'video_service_price_currency_sign',true);

    $converted_service['meeting_type']='';
    $meeting_type=get_post_meta($service->ID,'meeting_type',true);
    $converted_service['meeting_type_logic_value']= $meeting_type;

    if($meeting_type=='face-to-face')
    {
        $converted_service['meeting_type']=$textManager->getText('face_to_face_list_label');
        $converted_service['meettype_location']=get_post_meta($service->ID,'meettype_location',true);
    }
    if($meeting_type=='online-meeting')
    {
        $converted_service['meeting_type']=$textManager->getText('online_meeting_list_label');
        $converted_service['meettype_location']='';
    }

    um_reset_user();
    return $converted_service;
}

//handling human readable time

function timeAgo($datetime) {

    $now = new DateTime(current_time('mysql'));
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->y > 0) {
        return $diff->y . ' Année' . ($diff->y > 1 ? 's' : '') . '  il y a';
    } elseif ($diff->m > 0) {
        return $diff->m . ' Mois' . ($diff->m > 1 ? 's' : '') . '  il y a';
    } elseif ($diff->d > 0) {
        return $diff->d . ' Jour' . ($diff->d > 1 ? 's' : '') . '  il y a';
    } elseif ($diff->h > 0) {
        return $diff->h . ' Heure' . ($diff->h > 1 ? 's' : '') . ' Il y a';
    } elseif ($diff->i > 0) {
        return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . '  il y a';
    } elseif ($diff->s > 0) {
        return $diff->s . ' seconde' . ($diff->s > 1 ? 's' : '') . '  il y a';
    } else {
        return 'À l instant';
    }
}


function get_booking_status($booking_date, $booking_time) {
    // Combine the date and time
    $datetime_str = $booking_date . ' ' . $booking_time;
    $booking_datetime = new DateTime($datetime_str);

    // Get the current time
    $current_datetime = new DateTime(current_time('mysql'));

    // Calculate the difference in hours
    $interval = $current_datetime->diff($booking_datetime);
    $hours_difference = ($interval->days * 24) + $interval->h + ($interval->i / 60);
    
    // Determine status
    if ($booking_datetime > $current_datetime) {
        return 'upcoming';
    } elseif ($hours_difference <= 3) {
        return 'inprogress';
    } else {
        return 'overdue';
    }
}

add_action('wp_ajax_publish_unpublish_service','publish_unpublish_service_action');

function publish_unpublish_service_action()
{
    if(!is_user_logged_in())
    {
        die('');        
    }
    extract($_POST);
    $service_info=get_post($service_id);
    
    //only update status if the service exist and the right owner is taking action
    if ($service_info && $service_info->post_author == get_current_user_id()) 
    {
        $update_service_info=array('ID'=>$service_id,'post_status'=>'publish');
        if($service_info->post_status=='publish')
        {
            $update_service_info['post_status']='pending';
        }
        else
        {
            $update_service_info['post_status']='publish';
        }
        $update_result=wp_update_post($update_service_info);
        if($update_result && !is_wp_error($update_result))
        {
            $data['success']='true';
            $data['message']='Updated status successfully';
        }                
        else
        {
            $data['success']='false';
            $data['message']='Failed to update status';
        } 
    } 
    else 
    {
        $data['success']='false';
        $data['message']='No posts found !';
    }
    wp_send_json($data);
}   