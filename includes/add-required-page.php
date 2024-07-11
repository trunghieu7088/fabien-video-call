<?php


// ADD post service page TEMPLATE
add_filter( 'template_include', 'register_post_service_page', 99 );

function register_post_service_page($template)
{
     global $post;
     if(isset($post) && !empty($post))
     {
        $custom_template_slug   = 'custom-video-call-post-service-page.php';
        $page_template_slug     = get_page_template_slug( $post->ID );

        if( $page_template_slug == $custom_template_slug ){
        return CUSTOM_VIDEO_CALL_PATH .'/'.$custom_template_slug;
        }
    }
    return $template;
}

// ADD post service page TEMPLATE
add_filter( 'template_include', 'register_service_page_template', 99 );

function register_service_page_template($template)
{
     global $post;
     if(isset($post) && !empty($post))
     {
        $custom_template_slug   = 'custom-video-call-service-list-page.php';
        $page_template_slug     = get_page_template_slug( $post->ID );

        if( $page_template_slug == $custom_template_slug ){
        return CUSTOM_VIDEO_CALL_PATH .'/'.$custom_template_slug;
        }
    }
    return $template;
}

//add filter for single video service
add_filter( 'template_include', 'register_single_video_service_template', 99 );

function register_single_video_service_template($template)
{
    global $post;
    if(isset($post) && !empty($post) && is_singular('videoservice'))
    {       
        return CUSTOM_VIDEO_CALL_PATH .'/single-videoservice.php';    
    }
    return $template;
}


//add filter for booking management page
add_filter( 'template_include', 'register_booking_management_page_template', 99 );

function register_booking_management_page_template($template)
{
    global $post;
    if(isset($post) && !empty($post))
    {
       $custom_template_slug   = 'custom-video-call-booking-manage.php';
       $page_template_slug     = get_page_template_slug( $post->ID );

       if( $page_template_slug == $custom_template_slug ){
       return CUSTOM_VIDEO_CALL_PATH .'/'.$custom_template_slug;
       }
   }
   return $template;
}


//add page template video call room

add_filter( 'template_include', 'register_custom_video_call_room', 99 );

function register_custom_video_call_room($template)
{
    global $post;
    if(isset($post) && !empty($post))
    {
       $custom_template_slug   = 'custom-video-call-room.php';
       $page_template_slug     = get_page_template_slug( $post->ID );

       if( $page_template_slug == $custom_template_slug ){
       return CUSTOM_VIDEO_CALL_PATH .'/'.$custom_template_slug;
       }
   }
   return $template;
}


add_action('wp_loaded','addRequiredPage');

function addRequiredPage()
{
  
    //create post service page
    custom_check_and_create_page('custom-video-call-post-service-page.php','custom-post-service');

    //create list service page
    custom_check_and_create_page('custom-video-call-service-list-page.php','custom-service-list');

    //create booking manage page
    custom_check_and_create_page('custom-video-call-booking-manage.php','custom-manage-booking');    

    //create video call room
    custom_check_and_create_page('custom-video-call-room.php','video-call');

}

function custom_check_and_create_page($page_template,$page_slug)
{
    $args_page = array(
        'post_status' =>'publish',
        'post_type' => 'page', // Specify the post type as 'page'
        'posts_per_page' => -1, // Retrieve all pages (you can adjust this as needed)
        'meta_query' => array(
            array(
                'key' => '_wp_page_template', // The key for the template
                'value' => $page_template, // Replace with the template file name
                'compare' => '=', // Use '=' to match exactly
            ),
        ),
    );

    $args_page_check=get_posts($args_page);

    if(empty($args_page_check))
    {
        $args_page_Guid = site_url() . "/".$page_slug;
        $custom_created_page = array( 
            'post_title'     => $page_slug,
            'post_type'      => 'page',
            'post_name'      => $page_slug,                         
            'post_status'    => 'publish',
            'comment_status' => 'closed',
            'ping_status'    => 'closed',
            'post_author'    => 1,
            'menu_order'     => 0,
            'guid'           => $args_page_Guid );

        $created_page_id=wp_insert_post( $custom_created_page, FALSE ); 
        update_post_meta($created_page_id,'_wp_page_template',$page_template);
    }    
}