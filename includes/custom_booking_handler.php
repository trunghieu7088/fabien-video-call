<?php
function convert_booking_item($booking_item)
{ 

    $converted_booking['ID']=$booking_item->ID;
    $converted_booking['description']=$booking_item->post_content;
    $converted_booking['title']=$booking_item->post_title;

    $coach=get_user_by('ID',get_post_meta($booking_item->ID,'coach_id',true));
    $trainee=get_user_by('ID',get_post_meta($booking_item->ID,'trainee_id',true));

    //initialize UM info
    //$coach_um_user = um_fetch_user( $coach );

    //display name--> maybe change to match with plugin UM
   // $converted_service['service_owner_name']='Posted by '.um_user( 'display_name' );
    //$avatar=um_get_user_avatar_url( $service_owner->ID, '80' );
    //$converted_service['avatar']=$avatar;   
    //$converted_service['service_owner_url']= um_user_profile_url( $service_owner->ID);
     

    $converted_booking['coach_id']=$coach->ID;   
    $converted_booking['coach_name']=um_get_display_name($coach->ID);
    $converted_booking['coach_avatar']=um_get_user_avatar_url($coach->ID,'80');

    $converted_booking['trainee_id']=$trainee->ID;    
    $converted_booking['trainee_name']=um_get_display_name($trainee->ID);
    $converted_booking['trainee_avatar']=um_get_user_avatar_url($trainee->ID,'80');

    $converted_booking['service_id']=get_post_meta($booking_item->ID,'service_id',true);
    $converted_booking['service_url']=get_post_permalink($converted_booking['service_id']);

    $converted_booking['booking_amount']=get_post_meta($booking_item->ID,'booking_amount',true);
    $converted_booking['booking_currency_sign']=get_post_meta($booking_item->ID,'payment_currency_sign',true);
    $converted_booking['booking_currency_code']=get_post_meta($booking_item->ID,'payment_currency_code',true);

    

    $booking_time=get_post_meta($booking_item->ID,'booking_time',true);
    $converted_booking['booking_time']=$booking_time;

    $booking_date=get_post_meta($booking_item->ID,'booking_date',true);
    $converted_booking['booking_date']=$booking_date;

    //handling booking status
    $booking_status=get_post_meta($booking_item->ID,'booking_status',true);
    
    if($booking_status=='completed' || $booking_status=='cancelled')
    {    
        $converted_booking['booking_status']=$booking_status;
    }
    else
    {
        $converted_booking['booking_status']=get_booking_status($booking_date,$booking_time);
        update_post_meta($booking_item->ID,'booking_status',$converted_booking['booking_status']);
    }
    

    $converted_booking['booking_room_id']=get_post_meta($booking_item->ID,'booking_video_call_room_id',true);
    
    
   

    $date_obj = DateTime::createFromFormat('Y-m-d', $booking_date);
    $converted_booking['human_readable_date']= $date_obj->format('j F Y');

    $converted_booking['booking_note']= get_post_meta($booking_item->ID,'booking_note',true);
    $converted_booking['booking_type']= get_post_meta($booking_item->ID,'booking_type',true);
    $converted_booking['booking_location']=get_post_meta($booking_item->ID,'booking_location',true);

    $custom_texts = TextManager::getInstance();

    $converted_booking['new_title']= $custom_texts->getText('upcoming_booking_text');

    return $converted_booking;
}

function get_all_bookings($page_number=1,$booking_owner,$owner_type,$filter_condition=array())
{
    global $post;
    $booking_per_page=carbon_get_theme_option('custom_number_of_booking_each_page');
    

    $booking_args=array('post_type' => 'custom_booking',
    'posts_per_page' => $booking_per_page, 
    'paged'  =>$page_number,
    'post_status' =>'publish',        
    'meta_key'       => 'booking_date',   // The meta key to sort by.
    'orderby'        => 'meta_value',     // Sort by the value of the meta key.
    'meta_type'      => 'DATE',   
    'order'          => 'ASC',  
    );

    if(!empty($filter_condition))
    {
        if($filter_condition['order']!='')
        {
            $booking_args['order']=$filter_condition['order'];
        }
        if($filter_condition['status']!='' && $filter_condition['status']!='all')
        {
            $booking_args['meta_query'][]=array(
                'key'     => 'booking_status',
                'value'   => $filter_condition['status'],          
                'compare' => '=',   
            );            
        }

        if($filter_condition['type']!='' && $filter_condition['type']!='all')
        {
            $booking_args['meta_query'][]=array(
                'key'     => 'booking_type',
                'value'   => $filter_condition['type'],          
                'compare' => '=',   
            );            
        }
    }

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

function set_color_base_on_status($status)
{
    $color_collection=array('color'=>'custom-'.$status.'-booking','border'=>'custom-'.$status.'-border');
  
    return $color_collection;
}

function handling_display_join_cancel_buttons($status,$meet_type,$booking_room_id)
{
    $button_html_content='';
    if($meet_type=='face-to-face')
    {
        switch ($status) {
            case 'upcoming':
                $button_html_content='<a class="action-link cancel-btn-class" href="#"><i class="fa fa-circle-xmark"></i> Cancel</a>';
            break;

            case 'cancelled':
                $button_html_content='Cancel reason';
            break;

            case 'inprogress':
                $button_html_content.='<a href="#" class="action-link"><i class="fa fa-check-circle"></i>'.'complete'.'</a>';
                $button_html_content.='<a class="action-link cancel-btn-class" href="#"><i class="fa fa-circle-xmark"></i> Cancel</a>';
            break;
            
            case 'overdue':
                $button_html_content.='<a href="#" class="action-link"><i class="fa fa-check-circle"></i>'.'complete'.'</a>';
                $button_html_content.='<a class="action-link cancel-btn-class" href="#"><i class="fa fa-circle-xmark"></i> Cancel</a>';
            break;

            case 'completed':                
                $button_html_content='';
            break;
              
          }
                    
    }
    else
    {
        switch ($status) {
            case 'upcoming':     
                $button_html_content='<a class="action-link cancel-btn-class" href="#"><i class="fa fa-circle-xmark"></i> Cancel</a>';
            break;

            case 'cancelled':
              $button_html_content='Cancel reason';
            break;

            case 'inprogress':
                $button_html_content='<a href="#" class="action-link"><i class="fa fa-check-circle"></i>'.'complete'.'</a>';
                $button_html_content.='<a class="action-link" href="'.site_url('video-call').'/?videoroomid='.$booking_room_id.'">';
                $button_html_content.='<i class="fa fa-right-to-bracket"></i>'.'Join'.'</a>';
                $button_html_content.='<a class="action-link cancel-btn-class" href="#"><i class="fa fa-circle-xmark"></i> Cancel</a>';                
            break;
            
            case 'overdue':
                $button_html_content='<a href="#" class="action-link"><i class="fa fa-check-circle"></i>'.'complete'.'</a>';
                $button_html_content.='<a class="action-link" href="'.site_url('video-call').'/?videoroomid='.$booking_room_id.'">';
                $button_html_content.='<i class="fa fa-right-to-bracket"></i>'.'Join'.'</a>';
                $button_html_content.='<a class="action-link cancel-btn-class" href="#"><i class="fa fa-circle-xmark"></i> Cancel</a>';
            break;

            case 'completed':
               $button_html_content='';
            break;
              
          }
    }

    return $button_html_content;
}   

function init_booking_status()
{
    $status_collection=array('all'=>'All','upcoming'=>'Upcoming','cancelled'=>'Cancelled','inprogress'=>'In Progress','overdue'=>'Overdue','completed'=>'Completed');

    return $status_collection;
}