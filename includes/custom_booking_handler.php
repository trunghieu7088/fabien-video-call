<?php
function convert_booking_item($booking_item)
{
    $converted_booking['ID']=$booking_item->ID;
    $converted_booking['description']=$booking_item->post_content;
    $converted_booking['title']=$booking_item->post_title;

    $coach=get_user_by('ID',get_post_meta($booking_item->ID,'coach_id',true));
    $trainee=get_user_by('ID',get_post_meta($booking_item->ID,'trainee_id',true));

    $converted_booking['coach_id']=$coach->ID;
    $converted_booking['coach_name']=$coach->display_name;

    $converted_booking['trainee_id']=$trainee->ID;
    $converted_booking['trainee_name']=$trainee->display_name;

    $converted_booking['service_id']=get_post_meta($booking_item->ID,'service_id',true);

    $converted_booking['booking_amount']=get_post_meta($booking_item->ID,'booking_amount',true);
    $converted_booking['booking_currency_sign']=get_post_meta($booking_item->ID,'payment_currency_sign',true);
    $converted_booking['booking_currency_code']=get_post_meta($booking_item->ID,'payment_currency_code',true);

    $converted_booking['booking_status']=get_post_meta($booking_item->ID,'booking_status',true);
    $converted_booking['booking_time']=get_post_meta($booking_item->ID,'booking_time',true);

    $converted_booking['booking_room_id']=get_post_meta($booking_item->ID,'booking_video_call_room_id',true);
    
    
    $booking_date=get_post_meta($booking_item->ID,'booking_date',true);
    $converted_booking['booking_date']=$booking_date;

    $date_obj = DateTime::createFromFormat('Y-m-d', $booking_date);
    $converted_booking['human_readable_date']= $date_obj->format('j F Y');

    return $converted_booking;
}

function get_all_bookings($page_number=1,$booking_owner,$owner_type)
{
    global $post;
    $booking_args=array('post_type' => 'custom_booking',
    'posts_per_page' => -1, 
    'paged'  =>$page_number,
    'post_status' =>'publish',        
    'meta_key'       => 'booking_date',   // The meta key to sort by.
    'orderby'        => 'meta_value',     // Sort by the value of the meta key.
    'meta_type'      => 'DATE',   
    'order'          => 'ASC',  
    );

    if($owner_type=='trainee')
    {
        $booking_args['author']=$booking_owner;
    }

    if($owner_type=='coach')
    {
        $booking_args['meta_query'][]=array(
            'key' => 'coach_id',
            'value' => $booking_owner,
            'compare' => '=',
        );
    }

    $booking_query=new WP_Query($booking_args);

    $all_bookings=array();
    $all_bookings_info=array();
    if($booking_query->have_posts())
    {
        while($booking_query->have_posts())
        {
            $booking_query->the_post();
            $converted_booking=convert_booking_item($post);
            $all_bookings[]=$converted_booking;
        }
    }
    $all_bookings_info['booking_list']= $all_bookings;
    $all_bookings_info['max_num_pages']= $booking_query->max_num_pages;
    wp_reset_postdata();  

    return $all_bookings_info;
}

function get_all_busy_time($video_service_ID)
{
   $args_all_booking=array('post_type' => 'custom_booking',
   'posts_per_page' =>  -1,           
   'post_status'   => 'publish',
   'order'=>  'DESC',
   'orderby'=> 'date',  
   //add this meta query to make sure only get freelancers and complete profiles
   'meta_query'=> array(
                       array(
                           'key'=>'service_id',
                           'value'=>$video_service_ID,
                           'compare'=>'=',

                       ),
                   ),               
   );

   $busy_time_collection=array();
   $booking_collection=new WP_Query($args_all_booking);

   if($booking_collection->have_posts())
    {
        while($booking_collection->have_posts())
        {
            $booking_collection->the_post();  
            
            $busy_date=get_post_meta(get_the_ID(),'booking_date',true);
            $busy_time=get_post_meta(get_the_ID(),'booking_time',true);
            
            $converted_busy_time=DateTime::createFromFormat('h:i A', $busy_time);

            $busy_datetime= $busy_date.'T'.$converted_busy_time->format('H:i:s');

            $busy_time_collection[]=array(
                                'title'=>'busy',
                                'start'=>$busy_datetime,
                                );            
        }       

    }
    wp_reset_postdata(); 

    return $busy_time_collection;

}   

add_action('wp_head','handling_busy_time_single_service',999);

function handling_busy_time_single_service()
{
    global $post;
    if(is_single() && 'videoservice' == get_post_type())
    {
        $busy_time=get_all_busy_time($post->ID);        
        if(!empty($busy_time))
        {                    
            echo '<script type="text/template" id="busy-time-list">' . json_encode($busy_time) . '</script>';
        }
    }
}