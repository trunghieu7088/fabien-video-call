<?php
add_action('wp_enqueue_scripts', 'addAssetsFiles',1);
function addAssetsFiles()
{    
    
    wp_enqueue_style( 'custom-video-call-bootstrap-grid', CUSTOM_VIDEO_CALL_URL. 'assets/bootstrap4-6-2/css/bootstrap-grid.min.css', array(), SHARING_CUSTOM_VIDEO_CALL_VERSION ) ;    

    wp_enqueue_style( 'custom-video-call-style', CUSTOM_VIDEO_CALL_URL. 'assets/css/custom-video-call.css', array(), SHARING_CUSTOM_VIDEO_CALL_VERSION ) ;

    wp_enqueue_style( 'custom-video-call-toastr', CUSTOM_VIDEO_CALL_URL. 'assets/css/toastr.css', array(), SHARING_CUSTOM_VIDEO_CALL_VERSION ) ;

    wp_enqueue_script('custom-video-call-calendar-js', CUSTOM_VIDEO_CALL_URL.'assets/fullcalendar/index.global.min.js', array('jquery'), SHARING_CUSTOM_VIDEO_CALL_VERSION,false);    

    wp_enqueue_style( 'custom-video-call-timepicker', CUSTOM_VIDEO_CALL_URL. 'assets/timepicker/timepicker.css', array(), SHARING_CUSTOM_VIDEO_CALL_VERSION ) ;

    wp_enqueue_script('custom-video-call-timepicker-js', CUSTOM_VIDEO_CALL_URL.'assets/timepicker/timepicker.js', array('jquery'), SHARING_CUSTOM_VIDEO_CALL_VERSION,false);            
 
}

add_action('wp_enqueue_scripts', 'addboostrapfiles',999);
function addboostrapfiles()
{
    wp_enqueue_script('custom-video-call-bootstrap-js', CUSTOM_VIDEO_CALL_URL.'assets/bootstrap4-6-2/js/bootstrap.bundle.min.js', array(), SHARING_CUSTOM_VIDEO_CALL_VERSION);    

    wp_enqueue_script('custom-video-call-handle-js', CUSTOM_VIDEO_CALL_URL.'assets/js/custom-video-call-handle.js', array('jquery'), SHARING_CUSTOM_VIDEO_CALL_VERSION);    

    wp_enqueue_script('custom-video-call-toastr-js', CUSTOM_VIDEO_CALL_URL.'assets/js/toastr.js', array('jquery'), SHARING_CUSTOM_VIDEO_CALL_VERSION);    
    
    if(is_page_template('custom-video-call-post-service-page.php'))
    {
        wp_enqueue_script('plupload-all');
    }
    
 
    
    //wp_enqueue_script('zegocloud-creating-room', CUSTOM_VIDEO_CALL_URL.'assets/zegocloud/video-call-create-room-handling.js', array('jquery'), SHARING_CUSTOM_VIDEO_CALL_VERSION,true);        
    if(is_page_template('custom-video-call-room.php'))
    {
        wp_enqueue_script('zegocloud-js-lib', CUSTOM_VIDEO_CALL_URL.'assets/zegocloud/zego-uikit-prebuilt.js', array(), SHARING_CUSTOM_VIDEO_CALL_VERSION,true);        

        wp_enqueue_script('zegocloud-video-call-handling', CUSTOM_VIDEO_CALL_URL.'assets/zegocloud/video-call-zegocloud-handling.js', array('jquery'), SHARING_CUSTOM_VIDEO_CALL_VERSION,true);       
    }
  
}


add_action('wp_enqueue_scripts', 'importTomSelect',999);
function importTomSelect()
{

    wp_enqueue_script('tom-select-js', CUSTOM_VIDEO_CALL_URL.'/assets/tom-select/tom-select.js', array(
        'jquery',          
    ), '1.0', true); 

    wp_enqueue_style('tom-select-css', CUSTOM_VIDEO_CALL_URL.'/assets/tom-select/tom-select.css');
  
}

add_action('wp_head','set_up_ajax_url',10);

function set_up_ajax_url()
{
    $ajax_url=admin_url('admin-ajax.php');
    $stripeInfo=getStripeInfo();
    ?>
    <script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript">
        let custom_video_call_ajaxURL='<?php echo $ajax_url; ?>';
        let custom_stripe_public_key='<?php echo  $stripeInfo['stripe_public_key']; ?>';
    </script>
    <?php
}