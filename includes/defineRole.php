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
//add_action('wp_head','checkRoleCustomVideo',1);

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

function determine_role_by_id($user_id,$input_role,$check_role=false)
{   
    if($check_role==false)
    {
        if(is_user_logged_in())
        {
            $user_info=get_user_by('ID',$user_id);
            if(in_array($input_role,$user_info->roles))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    else
    {
        if(is_user_logged_in())
        {
            $user_info=get_user_by('ID',$user_id);
            if ( in_array( 'trainee', $user_info->roles, true ) ||  in_array( 'coach', $user_info->roles, true )) 
            {
                return true;    
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    
}

add_shortcode('define_role_block','define_role_block_init',999);

function define_role_block_init()
{
    ob_start();    
    $is_defined_role=determine_role_by_id(get_current_user_id(),'coach',true);

    ?>
    <div class="row define-role-block">
    <?php if(is_user_logged_in() && $is_defined_role==false): ?>
   
        <div class="col-md-12 col-sm-12">
            <p class="roleTitle">Please define your role</p>            
            <form class="define-role-form-block" id="identify-role-form">
                    <input type="hidden" name="action" value="custom_video_call_save_role">
                    <p><input type="radio" id="coach_selector" name="role_type" value="coach"> <span><strong>Coach </strong></span></p>
                    <p><input type="radio" id="student_selector" name="role_type" value="trainee"><span> <strong> Trainee </strong></span></p>
                    <div class="submit-button-container">
                        <button type="submit" class="custom-save-role-btn">Save</button>
                    </div>
                    
            </form>
        </div>
    <?php endif; ?>

    <?php if(is_user_logged_in() && $is_defined_role==true ): ?>
        <?php 
            $current_role=determine_role_by_id(get_current_user_id(),'coach'); 
            if($current_role==true) $current_role='Coach'; else $current_role='Trainee';
        ?>
        <div class="roleTitle">
            Your role has been defined as <?php echo $current_role; ?>
        </div>
    <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}