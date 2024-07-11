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
        $data['redirect_url']=site_url('/profil/');
    }

    wp_send_json($data);
    die();
}


function determine_role_by_id($user_id,$input_role,$check_role_exist=false)
{   
    if($check_role_exist==false)
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
    $textManager = TextManager::getInstance();
    ?>
    <div class="row define-role-block" id="custom-define-role-block">
    <?php if(is_user_logged_in() && $is_defined_role==false): ?>
   
        <div class="col-md-12 col-sm-12">
            <p class="roleTitle"><?php echo $textManager->getText('define_role_message'); ?></p>            
            <form class="define-role-form-block" id="identify-role-form">
                    <input type="hidden" name="action" value="custom_video_call_save_role">
                    <p><input type="radio" id="coach_selector" name="role_type" value="coach"> <span><strong><?php echo $textManager->getText('role_coach_label'); ?> </strong></span></p>
                    <p><input type="radio" id="student_selector" name="role_type" value="trainee"><span> <strong> <?php echo $textManager->getText('role_trainee_label'); ?> </strong></span></p>
                    <div class="submit-button-container">
                        <button type="submit" class="custom-save-role-btn"><?php echo $textManager->getText('save_role_button'); ?></button>
                    </div>
                    
            </form>
        </div>
    <?php endif; ?>

    <?php if(is_user_logged_in() && $is_defined_role==true ): ?>
        <?php 
            $current_role=determine_role_by_id(get_current_user_id(),'coach'); 
            if($current_role==true) $current_role= $textManager->getText('role_coach_label'); else $current_role= $textManager->getText('role_trainee_label');;
        ?>
        <div class="roleTitle">
        <?php echo $textManager->getText('defined_role_message'); ?> <?php echo $current_role; ?>
        </div>
    <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}