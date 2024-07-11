<?php
require('addAssetsFiles.php');
require('add-required-page.php');
require('defineRole.php');

require('custom_register_postype.php');
require('custom_post_service_handler.php');

require('custom_video_call_service_list_handler.php');

require('custom_load_stripe_sdk.php');

require('custom_booking_handler.php');

require('custom_translate_handler.php');



add_action( 'after_setup_theme', 'custom_video_call_crb_load',900,0 );
function custom_video_call_crb_load() {    
    if ( ! function_exists( 'carbon_get_post_meta' ) ) {
    require_once CUSTOM_VIDEO_CALL_PATH . '/includes/carbon/vendor/autoload.php';
    \Carbon_Fields\Carbon_Fields::boot();
    }
}

add_action( 'after_setup_theme', 'init_zego_cloud_token_lib',990,0 );
function init_zego_cloud_token_lib() {    

    require_once CUSTOM_VIDEO_CALL_PATH . '/includes/zegotoken/auto_loader.php';
}




require('custom_video_call_room.php');

require('custom_sending_email_feature.php');