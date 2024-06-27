<?php
add_action( 'after_setup_theme', 'custom_load_Stripe_sdk',999,0 );
function custom_load_Stripe_sdk() 
{    
    require_once CUSTOM_VIDEO_CALL_PATH . '/includes/stripe/vendor/autoload.php';    
}


function hanlding_stripe_connect_button($user_id)
{
    $stripe_info=getStripeInfo();

    $redirect_uri=$stripe_info['redirect_uri_callback'];
    $client_id=$stripe_info['stripe_client_id'];
    $stripe_redirect_url=$stripe_info['stripe_redirect_url'];   
    $stripe_connect_url = "$stripe_redirect_url&client_id=$client_id&scope=read_write&redirect_uri=".$redirect_uri;
    return  $stripe_connect_url;
}

function getStripeInfo()
{
    $stripe_info=array('stripe_client_id'=>carbon_get_theme_option('custom_video_stripe_client_id'),    
                        'stripe_public_key'=>carbon_get_theme_option('custom_video_call_stripe_pk'),
                        'stripe_secret_key'=>carbon_get_theme_option('custom_video_call_stripe_sk'),                        
                        'stripe_redirect_url'=>'https://connect.stripe.com/oauth/authorize?response_type=code',
                        'redirect_uri_callback'=>site_url().'/custom-stripe-handling/',
                        //'stripe_redirect_url'=>'https://connect.stripe.com/express/oauth/authorize',
                        //https://connect.stripe.com/oauth/authorize?response_type=code&client_id=ca_HR0BF28vJU6f6rrh92OhJUreoKljZHLm&scope=read_write
    );
    return $stripe_info;
}

function connect_stripe_callback_init()
{          
        if (isset($_GET['code']) && is_user_logged_in())
        {            
            $stripe_info=getStripeInfo();
            $code = $_GET['code'];
            $user_id = get_current_user_id();
            $response = wp_remote_post('https://connect.stripe.com/oauth/token', [
                'body' => [
                    'client_secret' => $stripe_info['stripe_secret_key'],
                    'code' => $code,
                    'grant_type' => 'authorization_code',
                ]
            ]);
    
            $body = json_decode(wp_remote_retrieve_body($response), true);
            if (isset($body['stripe_user_id'])) {
                update_user_meta($user_id, 'stripe_account_id', $body['stripe_user_id']);
                update_user_meta($user_id, 'stripe_connect_status', 'true');
            }
        }    
}

add_action('connect_stripe_callback','connect_stripe_callback_init');

add_action('wp_ajax_stripe_transfer_complete_payment','stripe_transfer_complete_payment_init');

function stripe_transfer_complete_payment_init()
{
    if(!is_user_logged_in())
    {
        $data['message']='something went wrong. Please refresh';
        wp_send_json($data);
        die();
    }
    $stripe = new \Stripe\StripeClient('sk_test_51Gs86JH2lSxcFNkyVwao0Ne2oaXqqaLwjm0MDGrUbSyWP6G6ogUmirX4UeCt1UGIi3lwtQm12ES9Md4ln5cOV6G000aTe6l99x');

    $result=$stripe->transfers->create([
        'amount' => 1000, //in cents
        'currency' => 'usd',
        'destination' => 'acct_1PNopkC0cCEAIlSG',
      ]);
    $data['result']=$result;
    $data['message']='success';
    wp_send_json($data);
    die();
}

add_action('wp_ajax_custom_video_service_order_checkout','custom_video_service_order_checkout_init');


function custom_video_service_order_checkout_init()
{
   
    if(!is_user_logged_in())
    {
        die('something went wrong');
    }
    if (!wp_verify_nonce($_POST['checkout_service_nonce'],'checkout_service_nonce')) {
        die('something went wrong');
    } 
    extract($_POST);
    $stripe_info=getStripeInfo();
    $stripe = new \Stripe\StripeClient($stripe_info['stripe_secret_key']);
    $custom_paymentIntent = $stripe->paymentIntents->create([
        'amount' => $service_price * 100, // convert usd to cents 
        'currency' =>$service_currency,
        'payment_method_types' => ['card'],
    ]);

    
    $data['selected_date']=$selected_date;
    $data['selected_time']=$selected_time;
    $data['billing_name']=$billing_name;    
    $data['client_secret']= $custom_paymentIntent->client_secret;
    $data['success']='true';
    wp_send_json($data);
    die();
}

//redirect the none-connected-stripe coach when they redirect to the pages.

add_action('wp_head','checkStripeConnect',2);

function checkStripeConnect()
{
    $custom_user_info=wp_get_current_user();
    $is_coach=false;
    $stripe_connect_status=false;
    if ( in_array( 'coach', $custom_user_info->roles, true )) 
    {
        $is_coach=true;    
    }
    if(get_user_meta($custom_user_info->ID,'stripe_connect_status',true)=='true')
    {
        $stripe_connect_status=true;
    }
    if($stripe_connect_status==false && $is_coach==true
    && (is_page('custom-post-service') || is_page('custom-service-list') || is_page('custom-manage-booking')))
    {                              
        //redirect to Stripe connect page
        wp_redirect('custom-stripe-callback');
               
    }     
}

add_action('wp_ajax_custom_video_service_complete_order','custom_video_service_complete_order_init');

function custom_video_service_complete_order_init()
{   
    if(!is_user_logged_in())
    {
        die('something went wrong');
    }
    extract($_POST);
    $service_info=get_post($service_id);
    $booking_args=array(
        'post_title'=>'Booking for '.$service_info->post_title,
        'post_status'=>'publish',
        'post_content'=>$service_info->post_content,
        'post_type'=>'custom_booking',
        'post_author'=>get_current_user_id(),
    );

    $booking_created=wp_insert_post($booking_args);

    update_post_meta($booking_created,'trainee_id',get_current_user_id());
    
    update_post_meta($booking_created,'coach_id',$service_info->post_author);    

    update_post_meta($booking_created,'service_id',$service_id);

    update_post_meta($booking_created,'payment_intent_id',$payment_intentID);

    update_post_meta($booking_created,'payment_intent_status',$payment_status);

    update_post_meta($booking_created,'booking_amount',$payment_amount);

    update_post_meta($booking_created,'payment_currency_code',$payment_currency_code);

    update_post_meta($booking_created,'payment_currency_sign',$payment_currency_sign);

    update_post_meta($booking_created,'payment_created_api',$payment_created);
    

    update_post_meta($booking_created,'booking_status','upcoming');    

    update_post_meta($booking_created,'booking_date',$booking_date);

    update_post_meta($booking_created,'booking_time',$booking_time);

    update_post_meta($booking_created,'booking_note',$booking_note);

    $video_room_id=uniqid(rand(1,10000));
    update_post_meta($booking_created,'booking_video_call_room_id',$video_room_id);

    $data['success']='true';
    $data['message']='Booking successfully';   
    $data['redirect_url']=site_url('custom-manage-booking');
    wp_send_json($data);
    die();
   
}