<?php
function custom_sending_email_feature($type,$recipient_email,$recipient_role,$booking_id)
{
    $to = $recipient_email;
    $body='';
    $subject = '';
    $booking_info=get_post($booking_id);
    $booking_link='<a href="'.site_url('/custom-manage-booking/').'">'.'this link'.'</a>';
    $headers = array('Content-Type: text/html; charset=UTF-8', 'From: '.get_option('admin_email'));
    switch ($type)
    {
        case 'booking':
            $subject = 'Your service has got an order !';
            $body=$booking_info->post_title.' has made. Please visit this link '.$booking_link;

        break;

        case 'complete':
            $subject = 'An order has been marked as completed';
            $body=$booking_info->post_title.' has been completed. The money has been transferred to your Stripe account. Please visit this link '.$booking_link;
        break;
        
        case 'cancel':
            $subject = 'An order has been marked as cancelled';
            $body=$booking_info->post_title.' has been cancelled. The money has been refunded. Please visit this link '.$booking_link;
        break;
    }
    wp_mail($to, $subject, $body, $headers);
}

//send email to coach when the trainee make an order successfully
add_action('custom_hook_action_booking','send_email_to_coach',99,1);
function send_email_to_coach($booking_id)
{
    $booking_info=get_post($booking_id);
    if($booking_info)
    {
        $coach_info=get_user_by('ID',get_post_meta($booking_info->ID,'coach_id',true));
        custom_sending_email_feature('booking',$coach_info->user_email,'coach',$booking_info->ID);
    }
        
}


//send email to coach when the trainee click complete button to complete booking
add_action('custom_hook_action_complete','send_email_to_coach_about_complete_booking',99,1);
function send_email_to_coach_about_complete_booking($booking_id)
{
    $booking_info=get_post($booking_id);
    if($booking_info)
    {
        $coach_info=get_user_by('ID',get_post_meta($booking_info->ID,'coach_id',true));
        custom_sending_email_feature('complete',$coach_info->user_email,'coach',$booking_info->ID);
    }

}



//send email to coach or trainee when one of them mark cancelled
add_action('custom_hook_action_cancel','send_email_about_cancel_booking',99,1);
function send_email_about_cancel_booking($booking_id)
{
    $booking_info=get_post($booking_id);
    if($booking_info)
    {
        $cancel_owner_id=get_post_meta($booking_info->ID,'cancel_owner_id',true);
        $coach_id=get_post_meta($booking_info->ID,'coach_id',true);

        if($cancel_owner_id==$coach_id)
        {
            //recipient = trainee
            $recipient_info=get_user_by('ID',get_post_meta($booking_info->ID,'trainee_id',true));
            $recipient_role='trainee';          
        }
        else
        {
            //recipient = coach
            $recipient_info=get_user_by('ID', $coach_id);
            $recipient_role='coach';
        }
        
        custom_sending_email_feature('cancel',$recipient_info->user_email,$recipient_role,$booking_info->ID);
    }    
    update_post_meta($booking_id,'cancel_info',$recipient_info->user_email);
}