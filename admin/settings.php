<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;


add_action('carbon_fields_register_fields', 'custom_video_call_settings', 999, 0);

function custom_video_call_settings()
{
    Container::make('theme_options', __('Custom Video Call Settings', 'crb'))
    ->set_icon('dashicons-admin-generic')
    ->set_page_menu_title('Custom Video Call Settings')
    ->set_page_menu_position(6)
    ->add_tab('Custom Stripe API Key', array(
        Field::make('text', 'custom_video_call_stripe_pk', __('Stripe public key'))->set_default_value('none'),
        Field::make('text', 'custom_video_call_stripe_sk', __('Stripe secret key'))->set_default_value('none'),
        Field::make('text', 'custom_video_stripe_client_id', __('Stripe client ID'))->set_default_value('none'),
    ))
    ->add_tab('Currency Settings', array(
        Field::make('text', 'custom_video_call_stripe_currency_code', __('Currency Code'))->set_default_value('usd'),
        Field::make('text', 'custom_video_call_stripe_currency_sign', __('Currency Sign'))->set_default_value('$'),      
        Field::make('text', 'custom_video_call_commission', __('Commission Rate (%)'))->set_default_value(10),      
    ))
    ->add_tab('General Settings', array(
        Field::make('text', 'custom_number_of_service_each_page', __('Number of service each pages'))->set_default_value(20),      
        Field::make('text', 'custom_number_of_booking_each_page', __('Number of booking each pages'))->set_default_value(20),      
        Field::make( 'file', 'custom_video_call_default_cover_img_service', __( 'Default Image for service' ) )->set_type( array('image' ) )->set_value_type( 'url' ),
    
    ))
    ->add_tab('ZegoCloud Video Call', array(
        Field::make('text', 'zegocloud_appid', __('App ID'))->set_default_value('none'),
        Field::make('text', 'zegocloud_serversecret', __('Server Secret'))->set_default_value('none'),
    ))
    
    ->add_tab('Translating Post service', array(     
        Field::make('text', 'post_a_service_label', __('Post a Service'))->set_default_value('Post a service'),
        Field::make('text', 'please_enter_the_service_title_label', __('Please enter the service title'))->set_default_value('Please enter the service title'),

        Field::make('text', 'service_title_label', __('Service Title'))->set_default_value('Service Title'),
        Field::make('text', 'please_enter_the_price_label', __('Please enter the price'))->set_default_value('Please enter the price'),
        Field::make('text', 'service_price_label', __('Service Price'))->set_default_value('Service Price'),
        
        Field::make('text', 'service_duration_label', __('Duration (minutes)'))->set_default_value('Duration (minutes)'),
        Field::make('text', 'enter_duration_label', __('Please enter the minutes'))->set_default_value('Please enter the minutes'),

        Field::make('text', 'meeting_type_label', __('Meeting Type'))->set_default_value('Meeting Type'),
        Field::make('text', 'online_meeting_label', __('Online Meeting'))->set_default_value('Online Meeting'),
        Field::make('text', 'face_to_face_label', __('face-to-face'))->set_default_value('face-to-face'),

        Field::make('text', 'post_service_location_label', __('Location'))->set_default_value('Location'),
        Field::make('text', 'please_enter_location_label', __('Please enter detailed location'))->set_default_value('Please enter detailed location'),

        Field::make('text', 'service_category_label', __('Service Category'))->set_default_value('Service Category'),
        Field::make('text', 'select_category_label', __('Select a category'))->set_default_value('Select a category'),

        Field::make('text', 'service_description_label', __('Service Description'))->set_default_value('Service Description'),
        Field::make('text', 'service_image_label', __('Service Image'))->set_default_value('Service Image'),

        Field::make('text', 'service_submit_label', __('Submit'))->set_default_value('Submit'),

        Field::make('text', 'service_submit_success_message', __('Submit service successfully'))->set_default_value('Submit service successfully'),
        Field::make('text', 'service_submit_fail_message', __('Failed to submit service'))->set_default_value('Failed to submit service'),

    ))
    ->add_tab('Translating Service', array(
        
        Field::make('text', 'all_service_label', __('All Services'))->set_default_value('All Services'),
        Field::make('text', 'search_service_label', __('Search service'))->set_default_value('Search service'),
        Field::make('text', 'select_category_list_label', __('Select a category'))->set_default_value('Select a category'),
        Field::make('text', 'latest_label', __('Latest'))->set_default_value('Latest'),
        Field::make('text', 'oldest_label', __('Oldest'))->set_default_value('Oldest'),
        Field::make('text', 'sort_by_meettype_label', __('Sort by meet type'))->set_default_value('Sort by meet type'),
        Field::make('text', 'search_go_button_label', __('Go'))->set_default_value('Go'),

        Field::make('text', 'online_meeting_list_label', __('Online Meeting'))->set_default_value('Online Meeting'),
        Field::make('text', 'face_to_face_list_label', __('face-to-face'))->set_default_value('face-to-face'),

        Field::make('text', 'published_list_label', __('Published'))->set_default_value('Published'),
        Field::make('text', 'duration_list_label', __('Duration'))->set_default_value('Duration'),

        Field::make('text', 'posted_by_label', __('Posted by'))->set_default_value('Posted by'),
        Field::make('text', 'only_my_service', __('Only my service'))->set_default_value('Only my service'),

        Field::make('text', 'publish_service_btn', __('Publish Service'))->set_default_value('Publish Service'),
        Field::make('text', 'unpublish_service_btn', __('Unpublish Service'))->set_default_value('Unpublish Service'),

        //detail service page
        Field::make('text', 'book_an_appointment', __('Book an appointment'))->set_default_value('Book an appointment'),
        Field::make('text', 'use_calendar_text', __('Use Calendar to pick date (yyyy-mm-dd)'))->set_default_value('Use Calendar to pick date (yyyy-mm-dd)'),
        Field::make('text', 'notice_text_placeholder', __('Contact methods or the notification message'))->set_default_value('Contact methods or the notification message'),
        Field::make('text', 'order_service_btn_label', __('Order Service'))->set_default_value('Order Service'),
        Field::make('text', 'billing_name_placeholder', __('Billing name'))->set_default_value('Billing name'),
    ))   
    ->add_tab('Translating Booking', array(
        Field::make('text', 'upcoming_booking_text', __('Upcoming'))->set_default_value('Upcoming'),
        Field::make('text', 'completed_booking_text', __('Compeleted'))->set_default_value('Completed'),
        Field::make('text', 'cancelled_booking_text', __('Cancelled'))->set_default_value('Cancelled'),
        Field::make('text', 'overdue_booking_text', __('Overdue'))->set_default_value('Overdue'),
        Field::make('text', 'inprogress_booking_text', __('In Progress'))->set_default_value('In Progress'),
       
        Field::make('text', 'join_button_label', __('Join'))->set_default_value('Join'),
        Field::make('text', 'cancel_button_label', __('Cancel'))->set_default_value('Cancel'),
        Field::make('text', 'trainee_label', __('Trainee'))->set_default_value('Trainee'),
        Field::make('text', 'coach_label', __('Coach'))->set_default_value('Coach'),
        Field::make('text', 'my_booking_label', __('My Booking'))->set_default_value('My Booking'),
      
        Field::make('text', 'face_to_face_booking_label', __('face-to-face'))->set_default_value('face-to-face'),
        Field::make('text', 'online_meeting_booking_label', __('Online Meeting'))->set_default_value('Online Meeting'),
        
        Field::make('text', 'booking_for_label', __('Booking for'))->set_default_value('Booking for'),

        Field::make('text', 'meet_type_booking_label', __('Type'))->set_default_value('Type'),
        Field::make('text', 'location_booking_label', __('Location'))->set_default_value('Location'),

        Field::make('text', 'notification_message_booking_label', __('Notification Message'))->set_default_value('Notification Message'),        

        Field::make('text', 'status_filter_booking_label', __('Status'))->set_default_value('Status'), 

        //date filter
        Field::make('text', 'date_filter_booking_label', __('Date'))->set_default_value('Date'),             
        Field::make('text', 'date_filter_latest_label', __('Latest'))->set_default_value('Latest'),     
        Field::make('text', 'date_filter_oldest_label', __('Oldest'))->set_default_value('Oldest'),     


        Field::make('text', 'meettype_filter_booking_label', __('Meet type'))->set_default_value('Meet type'),        
        Field::make('text', 'meettype_filter_all_label', __('All'))->set_default_value('All'),     
        Field::make('text', 'meettype_filter_face_label', __('Face-to-face'))->set_default_value('Face-to-face'),     
        Field::make('text', 'meettype_filter_online_label', __('Onine meeting'))->set_default_value('Onine meeting'),     

        Field::make('text', 'filter_btn_booking_label', __('Filter'))->set_default_value('Filter'),        

        //booking action
        Field::make('text', 'cancelled_by_text', __('Cancelled by'))->set_default_value('Cancelled by'),        
        Field::make('text', 'reason_text', __('Reason'))->set_default_value('Reason'),        
        Field::make('text', 'done_btn_label', __('Done'))->set_default_value('Done'),        

    ))
    ->add_tab('Translate User Profile', array(
        
        Field::make('text', 'role_coach_label', __('Coach'))->set_default_value('Coach'),
        Field::make('text', 'role_trainee_label', __('Trainee'))->set_default_value('Trainee'),

        Field::make('text', 'define_role_message', __('Please define your role'))->set_default_value('Please define your role'),        
        Field::make('text', 'save_role_button', __('Save'))->set_default_value('Save'),        
        
        Field::make('text', 'defined_role_message', __('Your role has been defined as'))->set_default_value('Your role has been defined as'),        
        
        Field::make('text', 'require_define_role_message', __('Only the coach or the trainee can access this page'))->set_default_value('Only the coach or the trainee can access this page'),        
        Field::make('text', 'update_role_message', __('Please update role here here.'))->set_default_value('Please update role here here.'),        

        Field::make('text', 'only_coach_post_service_message', __('Only coach who has connected Stripe account can post service.'))->set_default_value('Only coach who has connected Stripe account can post service.'),                

        Field::make('text', 'stripe_connect_require_message', __('You are the coach and you need to connect Stripe Account before posting service or get payments'))->set_default_value('You are the coach and you need to connect Stripe Account before posting service or get payments.'),                

        Field::make('text', 'stripe_connect_status_message', __('You have connected to Stripe'))->set_default_value('You have connected to Stripe'),                

        Field::make('text', 'stripe_connect_btn_label', __('Connect Stripe'))->set_default_value('Connect Stripe'),                
        Field::make('text', 'stripe_disconnect_btn_label', __('Disconnect Stripe'))->set_default_value('Disconnect Stripe'),                
    )); 

        


}


