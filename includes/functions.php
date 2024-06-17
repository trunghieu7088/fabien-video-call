<?php
require('addAssetsFiles.php');
require('add-required-page.php');
require('defineRole.php');
require('custom_video_call_profile_page_function.php');
require('custom_register_postype.php');
require('custom_post_service_handler.php');

require('custom_video_call_service_list_handler.php');

require('custom_load_stripe_sdk.php');

require('custom_booking_handler.php');




add_action( 'after_setup_theme', 'custom_video_call_crb_load',900,0 );
function custom_video_call_crb_load() {    
    if ( ! function_exists( 'carbon_get_post_meta' ) ) {
    require_once CUSTOM_VIDEO_CALL_PATH . '/includes/carbon/vendor/autoload.php';
    \Carbon_Fields\Carbon_Fields::boot();
    }
}

require('custom_video_call_room.php');