<?php
add_action( 'after_setup_theme', 'custom_load_Stripe_sdk',999,0 );
function custom_load_Stripe_sdk() 
{    
    require_once CUSTOM_VIDEO_CALL_PATH . '/includes/stripe/vendor/autoload.php';    
}


function hanlding_stripe_connect_button()
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
                        'redirect_uri_callback'=>site_url().'/myprofile/',                                                
    );
    return $stripe_info;
}

add_action('connect_stripe_callback','connect_stripe_callback_init');
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
            wp_redirect(site_url('/myprofile/'));
            exit();
        }    
}

add_action('wp_ajax_disconnect_stripe_account','disconnect_stripe_account_action');

function disconnect_stripe_account_action()
{
    if(!is_user_logged_in())
    {
        $data['message']='something went wrong. Please refresh';
        wp_send_json($data);
        die();
    }

    //remove stripe id and update stripe status from DB.

    update_user_meta(get_current_user_id(),'stripe_connect_status','false');
    update_user_meta(get_current_user_id(),'stripe_account_id','');

    $data['message']='Disconnect Stripe account successfully';
    $data['success']=true;
    wp_send_json($data);

    die();

}

add_action('wp_ajax_cancel_booking_and_refund','cancel_booking_and_refund_action');
function cancel_booking_and_refund_action()
{
    if(!is_user_logged_in())
    {
        $data['message']='something went wrong. Please refresh';
        wp_send_json($data);
        die();
    }
    extract($_POST);
    $booking_item_info=get_post($booking_id);
    if($booking_item_info)
    {
        $stripe_info=getStripeInfo();    
        $stripe = new \Stripe\StripeClient($stripe_info['stripe_secret_key']);
        $payment_intentID=get_post_meta($booking_item_info->ID,'payment_intent_id',true);
        $stripe->refunds->create(['payment_intent' => $payment_intentID]);
        $data['message']='Cancel booking successfully !';
        $data['success']='true';     

        update_post_meta($booking_item_info->ID,'booking_status','cancelled');
        update_post_meta($booking_item_info->ID,'cancel_reason',$cancel_reason);

    }
    else
    {
        $data['message']='failed to cancel booking';
        $data['success']='false';        
    }
    wp_send_json($data);
    die();
   
}

add_action('wp_ajax_stripe_transfer_complete_payment','stripe_transfer_complete_payment_init');

function stripe_transfer_complete_payment_init()
{
    if(!is_user_logged_in())
    {
        $data['message']='something went wrong. Please refresh';
        wp_send_json($data);
        die();
    }
    extract($_POST);
    $booking_item_info=get_post($booking_id);
    if($booking_item_info)
    {
        $stripe_info=getStripeInfo();    
        $stripe = new \Stripe\StripeClient($stripe_info['stripe_secret_key']);
        
        //get payment intent from DB
        $payment_intentID=get_post_meta($booking_item_info->ID,'payment_intent_id',true);

        //get real info of payment intent from Stripe
        $paymentIntent_stripe = $stripe->paymentIntents->retrieve($payment_intentID);

        $result=$stripe->transfers->create([
            'amount' => 1000, //in cents
            'currency' => 'usd',
            'destination' => 'acct_1PNopkC0cCEAIlSG',
        ]);
        $data['result']=$result;
        $data['message']='success';
    }
    else
    {
        $data['message']='failed to cancel booking';
        $data['success']='false';        
    }
    wp_send_json($data);
    die();
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

    update_post_meta($booking_created,'booking_type',$booking_type);

    update_post_meta($booking_created,'booking_location',$booking_location);

    $video_room_id=uniqid(rand(1,10000));
    update_post_meta($booking_created,'booking_video_call_room_id',$video_room_id);

    $data['success']='true';
    $data['message']='Booking successfully';   
    $data['redirect_url']=site_url('custom-manage-booking');
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
//add_action('wp_head','checkStripeConnect',2);
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

add_shortcode('stripe_connect_callback_shortcode','stripe_connect_callback_shortcode_action');

function stripe_connect_callback_shortcode_action()
{
    do_action('connect_stripe_callback');
}


add_shortcode('stripe_connect_area','stripe_connect_area_shortcode',99);

function stripe_connect_area_shortcode()
{
    ob_start();
    $custom_user_info=wp_get_current_user();
    $is_coach=determine_role_by_id(get_current_user_id(),'coach');
    $stripe_connect_status=false;
   
    if(get_user_meta($custom_user_info->ID,'stripe_connect_status',true)=='true')
    {
        $stripe_connect_status=true;
    }
  
    ?>
    <div id="stripe-connect-area">
        <?php if($stripe_connect_status==false && $is_coach==true): ?>
        <div class="row stripe-connect-container">        
                <div class="stripe-connect-title">You are the coach and you need to connect Stripe Account before posting service or get payments</div>
                <a href="<?php echo hanlding_stripe_connect_button(); ?>" class="stripe-connect-link">Connect Stripe</a>        
        </div>
        <?php endif; ?>

        <?php if($stripe_connect_status==true && $is_coach==true): ?>
        <div class="row stripe-connect-container">        
                <div class="stripe-connect-title">You have connected to Stripe</div>
                <a href="javascript:void(0)" id="disconnect-stripe-account" class="stripe-connect-link">Disconnect Stripe</a>        
        </div>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}