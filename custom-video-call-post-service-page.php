<?php
/*
Template Name: Custom Video Call Post Service page
*/
?>
<?php
//redirect none-logged user to homepage
if(!is_user_logged_in())
{
    wp_redirect(site_url());
}
//redirect trainee to the list service page
$custom_user_info=wp_get_current_user();
if ( !in_array( 'coach', $custom_user_info->roles, true )) 
{
    wp_redirect(site_url('custom-service-list'));
}

get_header();
?>
<?php 
$service_category_list=get_all_service_category();
?>
<div class="container">
    <div class="post-service-section">
        <p class="post-service-title">Post a service</p>
        <form class="post-service-form" id="custom-post-service-form" name="custom-post-service-form">
            <input type="hidden" id="action" name="action" value="create_video_call_service">
            <input type="hidden" name="create_video_call_service_nonce" id="create_video_call_service_nonce" value="<?php echo wp_create_nonce('create_video_call_service_nonce'); ?>">
            <input type="hidden" name="custom_service_currency_sign" id="custom_service_currency_sign" value="<?php echo carbon_get_theme_option('custom_video_call_stripe_currency_sign'); ?>">
            <input type="hidden" name="custom_service_currency_code" id="custom_service_currency_code" value="<?php echo carbon_get_theme_option('custom_video_call_stripe_currency_code'); ?>">
            <div class="post-service-form-group">
                <label>Service Title</label>
                <input type="text" placeholder="Please enter the service title..." name="service_title" id="service_title" required="">
            </div>

            <div class="post-service-form-group">
                <label>Service Price <?php echo '('.carbon_get_theme_option('custom_video_call_stripe_currency_sign').')'; ?></label>
                <input type="text" placeholder="Please enter the price..." name="service_price" id="service_price" required="">
            </div>

            <div class="post-service-form-group">
                <label>Duration (minutes)</label>
                <input type="text" placeholder="Please enter the minutes..." name="service_duration" id="service_duration" required="">
            </div>   
            
            <div class="post-service-form-group">
                <label>Meeting Type</label>
                <select id="meeting_type" name="meeting_type" placeholder="Select an option" autocomplete="off">                    
                    <option value="online-meeting">Online Meeting</option>
                    <option value="face-to-face">Face to Face</option>                   
                </select>   
            </div>

            <div class="post-service-form-group" style="display:none;">
                <label>Location</label>
                <input type="text" placeholder="Please enter detailed location" name="meettype_location" id="meettype_location">
            </div>   


            <div class="post-service-form-group">
                <label>Service Category</label>
                <select id="service_category" name="service_category" placeholder="Select a category" autocomplete="off">
                    <option value="">Select a category</option>
                        <?php foreach($service_category_list as $service_category): ?>
                            <option value="<?php echo $service_category->term_id ?>"><?php echo $service_category->name;?></option>
                        <?php endforeach; ?>
                </select>
            </div>

          
          

            <div class="post-service-form-group">
                <label>Service Description</label>
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
                <label>Service Image</label>
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
            <button type="submit" class="video-call-btn-upload-image">Submit</button>
            </div>

        </form>
    </div>
</div>
<?php
get_footer();