<?php
//ADD CUSTOM ROLES
function custom_video_call_add_custom_role()
{
    $role_info=get_role('author'); //get capabilities of author
    add_role('coach','Coach',$role_info->capabilities );
    add_role('trainee','Trainee',$role_info->capabilities );
}
add_action('init','custom_video_call_add_custom_role');


add_action('wp_ajax_custom_video_call_save_role','custom_video_call_save_role_action');

function custom_video_call_save_role_action()
{
    if (!is_user_logged_in()) {
        die();
    }
    extract($_POST);
    $custom_user_info=wp_get_current_user();
    $custom_user_info->add_role($role_type);  
    $data['success']='true';
    $data['message']='ok';   
    $data['redirect_url']=site_url('custom-profile');
    wp_send_json($data);
    die();
}

//add_action('wp_loaded','checkmyrole',999);

function checkmyrole()
{
    $custom_user_info=wp_get_current_user();
    if ( in_array( 'trainee', $custom_user_info->roles, true ) ) {
        echo 'trainee';        
    }
    else
    {
        echo 'meo phari';
    }
    
}