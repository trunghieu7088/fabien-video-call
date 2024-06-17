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
    if(wp_get_referer())
    {
        $data['redirect_url']=wp_get_referer();
    }
    else
    {
        $data['redirect_url']='custom-list-service';
    }

    wp_send_json($data);
    die();
}

//redirect the none-defined users to the define role if they access to custom video call plugin pages
add_action('wp_head','checkRoleCustomVideo',1);

function checkRoleCustomVideo()
{
    $custom_user_info=wp_get_current_user();
    $is_defined_role=false;
    if ( in_array( 'trainee', $custom_user_info->roles, true ) ||  in_array( 'coach', $custom_user_info->roles, true )) 
    {
        $is_defined_role=true;    
    }
    if(is_page('custom-post-service') || is_page('custom-service-list') || is_page('custom-manage-booking'))
    {                        
        if($is_defined_role==false)
        {
            wp_redirect('definerole');
        }           
    }     
    if($is_defined_role==true && is_page('definerole'))     
    {
        wp_redirect('custom-service-list');
    }  
}
