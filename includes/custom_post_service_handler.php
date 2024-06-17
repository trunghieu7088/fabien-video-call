<?php
function get_all_service_category()
{
    $terms = get_terms(array(
        'taxonomy' => 'service_category',
        'hide_empty' => false,
    ));
    return $terms;
}


add_action('wp_ajax_create_video_call_service','create_video_call_service_action');

function create_video_call_service_action()
{
    if (!is_user_logged_in()) {
        die();
    }
    if (!wp_verify_nonce($_POST['create_video_call_service_nonce'],'create_video_call_service_nonce')) {
        die('something went wrong');
    } 
   
    
    extract($_POST);
    $video_call_args=array(
                            'post_title'=>$service_title,
                            'post_status'=>'publish',
                            'post_content'=>$service_description,
                            'post_type'=>'videoservice',
                );

    $video_call_service_created=wp_insert_post($video_call_args);
    if($video_call_service_created && !is_wp_error($video_call_service_created))
    {
        update_post_meta($video_call_service_created,'video_service_price',$service_price);
        update_post_meta($video_call_service_created,'video_service_price_currency_code',$custom_service_currency_code);
        update_post_meta($video_call_service_created,'video_service_price_currency_sign',$custom_service_currency_sign);
        
        update_post_meta($video_call_service_created,'video_service_duration',$service_duration);
        update_post_meta($video_call_service_created,'video_service_duration_type','minutes');

        wp_set_post_terms($video_call_service_created,array((int)$service_category),'service_category');

        if(!empty($image_attach_id))
        {
            $attach_update = array('ID' => $image_attach_id, 'post_parent' => $video_call_service_created);
            wp_update_post($attach_update);
        }

        $data['success']='true';
        $data['message']='Submit service successfully';   
        $data['redirect_url']=site_url('custom-service-list');
    }
    else
    {
        $data['success']='false';
        $data['message']='failed to create service';   
        $data['redirect_url']=site_url('custom-post-service');
    }
   
    wp_send_json($data);
    die();
}

//handling upload image

add_action('wp_ajax_service_image_uploader', 'service_image_uploader_action');

function service_image_uploader_action()
{
    if (!is_user_logged_in()) {
        die();
    }
    if (!check_ajax_referer('service_image_uploader_nonce', $_POST['_ajax_nonce'])) {
        die();
    }

    if (!function_exists('wp_handle_upload')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }

    $upload_dir = wp_upload_dir();
    $upload_overrides = array( 'test_form' => false );

    if (!empty($_FILES['file'])) {
        $uploaded_file = $_FILES['file'];
    }

    $movefile = wp_handle_upload( $uploaded_file, $upload_overrides );

    $attachment = array(       
        'guid'  => $upload_dir['url'].'/'.sanitize_file_name($uploaded_file['name']), 
        'post_mime_type' => $uploaded_file['type'],
        'post_title'     =>  sanitize_file_name($uploaded_file['name']),
        'post_content'   => 'image of custom video call service',
        'post_status'    => 'inherit'
    );

    $attach_id = wp_insert_attachment($attachment, $uploaded_file['name']);   
    
    if($attach_id)
    {
        update_post_meta($attach_id,'_wp_attached_file',substr($upload_dir['subdir'],1).'/'.sanitize_file_name($uploaded_file['name']));                      
        update_post_meta($attach_id,'custom_attachment_type','video_call_service_item');        
    }

    if ( $movefile && ! isset( $movefile['error'] ) ) {
        $data['success']='true';
        $data['attach_id']= $attach_id;

        $data['url_uploaded']=wp_get_attachment_image_url($attach_id,'thumbnail');        
    } else {
        $data['success']='false';
    }
    wp_send_json($data);

}
