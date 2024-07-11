<?php
/*
Template Name: Custom Video Call Post Service page
*/
?>
<?php
//redirect none-logged user to login page
if(!is_user_logged_in())
{
    wp_redirect(site_url('/identification/'));
}
else
{    
    $stripe_connected=check_stripe_account_connect(get_current_user_id());    
    $is_coach=determine_role_by_id(get_current_user_id(),'coach'); 
}

get_header();
$textManager = TextManager::getInstance();
?>
<?php 
$service_category_list=get_all_service_category();
?>
<div class="container">
    <div class="post-service-section">
    <?php if($stripe_connected==true && $is_coach==true): ?>        
        <p class="post-service-title"><?php echo $textManager->getText('post_a_service_label');?></p>
        <form class="post-service-form" id="custom-post-service-form" name="custom-post-service-form">
            <input type="hidden" id="action" name="action" value="create_video_call_service">
            <input type="hidden" name="create_video_call_service_nonce" id="create_video_call_service_nonce" value="<?php echo wp_create_nonce('create_video_call_service_nonce'); ?>">
            <input type="hidden" name="custom_service_currency_sign" id="custom_service_currency_sign" value="<?php echo carbon_get_theme_option('custom_video_call_stripe_currency_sign'); ?>">
            <input type="hidden" name="custom_service_currency_code" id="custom_service_currency_code" value="<?php echo carbon_get_theme_option('custom_video_call_stripe_currency_code'); ?>">
            <div class="post-service-form-group">
                <label><?php echo $textManager->getText('service_title_label');?></label>
                <input type="text" placeholder="<?php echo $textManager->getText('please_enter_the_service_title_label');?>" name="service_title" id="service_title" required="">
            </div>

            <div class="post-service-form-group">
                <label><?php echo $textManager->getText('service_price_label');?> <?php echo '('.carbon_get_theme_option('custom_video_call_stripe_currency_sign').')'; ?></label>
                <input type="text" placeholder="<?php echo $textManager->getText('please_enter_the_price_label');?>" name="service_price" id="service_price" required="">
            </div>

            <div class="post-service-form-group">
                <label><?php echo $textManager->getText('service_duration_label');?></label>
                <input type="text" placeholder="<?php echo $textManager->getText('enter_duration_label');?>" name="service_duration" id="service_duration" required="">
            </div>   
            
            <div class="post-service-form-group">
                <label><?php echo $textManager->getText('meeting_type_label');?></label>
                <select id="meeting_type" name="meeting_type" placeholder="Select an option" autocomplete="off">                    
                    <option value="online-meeting"><?php echo $textManager->getText('online_meeting_label');?></option>
                    <option value="face-to-face"><?php echo $textManager->getText('face_to_face_label');?></option>                   
                </select>   
            </div>

            <div class="post-service-form-group" style="display:none;">
                <label><?php echo $textManager->getText('post_service_location_label');?></label>
                <input type="text" placeholder="<?php echo $textManager->getText('please_enter_location_label');?>" name="meettype_location" id="meettype_location">
            </div>   


            <div class="post-service-form-group">
                <label><?php echo $textManager->getText('service_category_label');?></label>
                <select id="service_category" name="service_category" placeholder="<?php echo $textManager->getText('select_category_label');?>" autocomplete="off">
                    <option value=""><?php echo $textManager->getText('select_category_label');?></option>
                        <?php foreach($service_category_list as $service_category): ?>
                            <option value="<?php echo $service_category->term_id ?>"><?php echo $service_category->name;?></option>
                        <?php endforeach; ?>
                </select>
            </div>

          
          

            <div class="post-service-form-group">
                <label><?php echo $textManager->getText('service_description_label');?></label>
                <?php 
                $wp_editor_settings = array(
                    'textarea_name'=>'service_description',
                    'media_buttons' => false,
                    'quicktags' => false,
                    'teeny'         => true,
                    'textarea_rows' =>10,
                    'tinymce'       => array(
                        'toolbar1'      => 'bold,italic,underline,separator,alignleft,aligncenter,alignright',
                        'toolbar2'      => '',
                        'toolbar3'      => '',
                    ),
                );
                wp_editor( '', 'service_description', $wp_editor_settings );
                 ?>
            </div>

            <div class="post-service-form-group">
                <label><?php echo $textManager->getText('service_image_label');?></label>
                <div class="service-image-upload-container" id="service-image-upload-container">
                    <a type="button" class="video-call-btn-upload-image" id="service-image-upload-btn"><i class="fa fa-plus"></i> <i class="fa fa-image"></i></a>
                    <input type="hidden" name="service_image_uploader_nonce" id="service_image_uploader_nonce" value="<?php echo wp_create_nonce('service_image_uploader_nonce'); ?>">
                </div>
                <div class="uploaded-image-section">
                    <img id="uploaded_image" src="">
                </div>
                <input type="hidden" name="image_attach_id" id="image_attach_id">
                

            </div>

            <div class="post-service-form-group">
            <button type="submit" class="video-call-btn-upload-image"><?php echo $textManager->getText('service_submit_label');?></button>
            </div>

        </form>
        <?php else:?>
            <h3 style="text-align:center;"><?php echo $textManager->getText('only_coach_post_service_message'); ?></h3>
            <p style="text-align:center;"><a href="<?php echo site_url('/profil/#custom-define-role-block'); ?>"><?php echo $textManager->getText('update_role_message'); ?></a></p>
        <?php endif; ?>
    </div>
</div>
<?php
get_footer();