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
        // Gán giá trị cho các văn bản cần dịch
        $this->texts = [
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
            'all_services_label' => carbon_get_theme_option('all_services_label') ?: __('All Services'),
            'face_to_face_label' => carbon_get_theme_option('face_to_face_label') ?: __('face-to-face'),
            'online_meeting_label' => carbon_get_theme_option('online_meeting_label') ?: __('Online Meeting'),
            'post_a_service_label' => carbon_get_theme_option('post_a_service_label') ?: __('Post a Service'),
            'service_title_label' => carbon_get_theme_option('service_title_label') ?: __('Service Title')
        ];
    }

    public function getText($key) {
        return isset($this->texts[$key]) ? $this->texts[$key] : null;
    }
}

    
}