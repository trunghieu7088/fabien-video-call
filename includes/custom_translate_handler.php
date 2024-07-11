<?php
add_action('init','create_translator_global_instance',999);

function create_translator_global_instance()
{

class TextManager {
    private static $instance = null;
    private $texts = [];

    private function __construct() {
        $this->loadTexts();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new TextManager();
        }
        return self::$instance;
    }

    private function loadTexts() {        
        $this->texts = [
            'post_a_service_label' => carbon_get_theme_option('post_a_service_label') ?: __('Post a Service'),
            'please_enter_the_service_title_label' => carbon_get_theme_option('please_enter_the_service_title_label') ?: __('Please enter the service title'),
            'service_title_label' => carbon_get_theme_option('service_title_label') ?: __('Service Title'),
            'please_enter_the_price_label' => carbon_get_theme_option('please_enter_the_price_label') ?: __('Please enter the price'),
            'service_duration_label' => carbon_get_theme_option('service_duration_label') ?: __('Duration (minutes)'),
            'enter_duration_label' => carbon_get_theme_option('enter_duration_label') ?: __('Please enter the minutes'),
            'meeting_type_label' => carbon_get_theme_option('meeting_type_label') ?: __('Meeting Type'),
            'online_meeting_label' => carbon_get_theme_option('online_meeting_label') ?: __('Online Meeting'),
            'face_to_face_label' => carbon_get_theme_option('face_to_face_label') ?: __('face-to-face'),
            'service_category_label' => carbon_get_theme_option('service_category_label') ?: __('Service Category'),
            'select_category_label' => carbon_get_theme_option('select_category_label') ?: __('Select a category'),
            'service_description_label' => carbon_get_theme_option('service_description_label') ?: __('Service Description'),
            'service_image_label' => carbon_get_theme_option('service_image_label') ?: __('Service Image'),
            'service_submit_label' => carbon_get_theme_option('service_submit_label') ?: __('Submit'),
            'all_service_label' => carbon_get_theme_option('all_service_label') ?: __('All Services'),
            'search_service_label' => carbon_get_theme_option('search_service_label') ?: __('Search service'),
            'select_category_list_label' => carbon_get_theme_option('select_category_list_label') ?: __('Select a category'),
            'latest_label' => carbon_get_theme_option('latest_label') ?: __('Latest'),
            'oldest_label' => carbon_get_theme_option('oldest_label') ?: __('Oldest'),
            'sort_by_meettype_label' => carbon_get_theme_option('sort_by_meettype_label') ?: __('Sort by meet type'),
            'search_go_button_label' => carbon_get_theme_option('search_go_button_label') ?: __('Go'),
            'online_meeting_list_label' => carbon_get_theme_option('online_meeting_list_label') ?: __('Online Meeting'),
            'face_to_face_list_label' => carbon_get_theme_option('face_to_face_list_label') ?: __('face-to-face'),
            'published_list_label' => carbon_get_theme_option('published_list_label') ?: __('Published'),
            'duration_list_label' => carbon_get_theme_option('duration_list_label') ?: __('Duration'),
            'posted_by_label' => carbon_get_theme_option('posted_by_label') ?: __('Posted by'),
            'book_an_appointment' => carbon_get_theme_option('book_an_appointment') ?: __('Book an appointment'),
            'use_calendar_text' => carbon_get_theme_option('use_calendar_text') ?: __('Use Calendar to pick date (yyyy-mm-dd)'),
            'notice_text_placeholder' => carbon_get_theme_option('notice_text_placeholder') ?: __('Contact methods or the notification message'),
            'upcoming_booking_text' => carbon_get_theme_option('upcoming_booking_text') ?: __('Upcoming'),
            'completed_booking_text' => carbon_get_theme_option('completed_booking_text') ?: __('Completed'),
            'cancelled_booking_text' => carbon_get_theme_option('cancelled_booking_text') ?: __('Cancelled'),
            'overdue_booking_text' => carbon_get_theme_option('overdue_booking_text') ?: __('Overdue'),
            'inprogress_booking_text' => carbon_get_theme_option('inprogress_booking_text') ?: __('In Progress'),
            'join_button_label' => carbon_get_theme_option('join_button_label') ?: __('Join'),
            'cancel_button_label' => carbon_get_theme_option('cancel_button_label') ?: __('Cancel'),
            'trainee_label' => carbon_get_theme_option('trainee_label') ?: __('Trainee'),
            'coach_label' => carbon_get_theme_option('coach_label') ?: __('Coach'),
            'my_booking_label' => carbon_get_theme_option('my_booking_label') ?: __('My Booking'),
            'face_to_face_booking_label' => carbon_get_theme_option('face_to_face_booking_label') ?: __('face-to-face'),
            'online_meeting_booking_label' => carbon_get_theme_option('online_meeting_booking_label') ?: __('Online Meeting'),
            'booking_for_label' => carbon_get_theme_option('booking_for_label') ?: __('Booking for'),
            'meet_type_booking_label' => carbon_get_theme_option('meet_type_booking_label') ?: __('Type'),
            'location_booking_label' => carbon_get_theme_option('location_booking_label') ?: __('Location'),
            'notification_message_booking_label' => carbon_get_theme_option('notification_message_booking_label') ?: __('Notification Message'),
            'service_price_label' => carbon_get_theme_option('service_price_label') ?: __('Service Price'),

            'post_service_location_label' => carbon_get_theme_option('post_service_location_label') ?: __('Location'),
            'please_enter_location_label' => carbon_get_theme_option('please_enter_location_label') ?: __('Please enter detailed location'),

            'service_submit_success_message' => carbon_get_theme_option('service_submit_success_message') ?: __('Submit service successfully'),
            'service_submit_fail_message' => carbon_get_theme_option('service_submit_fail_message') ?: __('Failed to submit service'),

            'order_service_btn_label' => carbon_get_theme_option('order_service_btn_label') ?: __('Order Service'),
            'billing_name_placeholder' => carbon_get_theme_option('billing_name_placeholder') ?: __('Billing name'),
            
            'only_my_service' => carbon_get_theme_option('only_my_service') ?: __('Only my service'),

            'publish_service_btn' => carbon_get_theme_option('publish_service_btn') ?: __('Publish service'),
            'unpublish_service_btn' => carbon_get_theme_option('unpublish_service_btn') ?: __('Unpublish service'),
                    
            //role define
            'role_coach_label' => carbon_get_theme_option('role_coach_label') ?: __('Coach'),

            'role_trainee_label' => carbon_get_theme_option('role_trainee_label') ?: __('Trainee'),
            'define_role_message' => carbon_get_theme_option('define_role_message') ?: __('Please define your role'),
                    
            'save_role_button' => carbon_get_theme_option('save_role_button') ?: __('Save'),

            'defined_role_message' => carbon_get_theme_option('defined_role_message') ?: __('your role has been defined as'),

            'require_define_role_message' => carbon_get_theme_option('require_define_role_message') ?: __('Only the coach or the trainee can access this page'),
            'update_role_message' => carbon_get_theme_option('update_role_message') ?: __('Please update role here here.'),
            
            'only_coach_post_service_message' => carbon_get_theme_option('only_coach_post_service_message') ?: __('Only coach who has connected Stripe account can post service.'),
            
            'status_filter_booking_label' => carbon_get_theme_option('status_filter_booking_label') ?: __('Status'),
            
            'date_filter_booking_label' => carbon_get_theme_option('date_filter_booking_label') ?: __('Date'),
            'date_filter_latest_label' => carbon_get_theme_option('date_filter_latest_label') ?: __('Latest'),
            'date_filter_oldest_label' => carbon_get_theme_option('date_filter_oldest_label') ?: __('Oldest'),
               
    
            'meettype_filter_booking_label' => carbon_get_theme_option('meettype_filter_booking_label') ?: __('Meet type'),
            'meettype_filter_all_label' => carbon_get_theme_option('meettype_filter_all_label') ?: __('All'),
            'meettype_filter_face_label' => carbon_get_theme_option('meettype_filter_face_label') ?: __('Face-to-face'),
            'meettype_filter_online_label' => carbon_get_theme_option('meettype_filter_online_label') ?: __('Onine meeting'),
            
            'filter_btn_booking_label' => carbon_get_theme_option('filter_btn_booking_label') ?: __('Filter'),                        
              
            'cancelled_by_text' => carbon_get_theme_option('cancelled_by_text') ?: __('Cancelled by'),                        
            'reason_text' => carbon_get_theme_option('reason_text') ?: __('Reason'),                        
            'done_btn_label' => carbon_get_theme_option('done_btn_label') ?: __('Done'),  


            'stripe_connect_require_message' => carbon_get_theme_option('stripe_connect_require_message') ?: __('You are the coach and you need to connect Stripe Account before posting service or get payments'),  
            'stripe_connect_status_message' => carbon_get_theme_option('stripe_connect_status_message') ?: __('You have connected to Stripe'),  
            'stripe_connect_btn_label' => carbon_get_theme_option('stripe_connect_btn_label') ?: __('Connect Stripe'),  
            'stripe_disconnect_btn_label' => carbon_get_theme_option('stripe_disconnect_btn_label') ?: __('Disconnect Stripe'),                      
        
            
        ];
    }

    public function getText($key) {
        return isset($this->texts[$key]) ? $this->texts[$key] : null;
    }
}

    
}