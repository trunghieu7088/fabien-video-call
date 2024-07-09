<?php
use ZEGO\ZegoServerAssistant;
use ZEGO\ZegoErrorCodes;


add_action('wp_ajax_generate_secure_token_zego','generate_secure_token_for_zegocloud');

function generate_secure_token_for_zegocloud()
{
    $user_id=strval(get_current_user_id());    
    if(is_user_logged_in())
    {
        $zego_cloud_appid=(int)carbon_get_theme_option('zegocloud_appid');
        $zego_cloud_secret=carbon_get_theme_option('zegocloud_serversecret');
        $payload = '';
        $token = ZegoServerAssistant::generateToken04($zego_cloud_appid,$user_id,$zego_cloud_secret,7200,$payload);
        if( $token->code == ZegoErrorCodes::success ){           
           $data['success']='true';
           $data['token']=$token;
          wp_send_json($data);
        
        }
        else
        {
            $data['success']='false';
            $data['token']='';
            wp_send_json($data);
        }    
    }
    else
    {
        $data['success']='false';
        $data['token']='';
        wp_send_json($data);
    }
    die();
}


add_action('wp_head','get_zegocloud_info',1);

function get_zegocloud_info()
{
    $zego_cloud_appid=carbon_get_theme_option('zegocloud_appid');    
    
    $custom_ajax_action_url=site_url().'/wp-admin/admin-ajax.php?action=generate_secure_token_zego';    
    if(is_user_logged_in())
    {
        $current_user_info=wp_get_current_user();        
        $zego_cloud_name_display=um_get_display_name($current_user_info->ID);  
       
    }
    else
    {
        $zego_cloud_name_display='username';       
    }   
    ?>
    <script>
        let zegocloud_appid=<?php echo $zego_cloud_appid; ?>;       
        let zegocloud_display_name="<?php echo $zego_cloud_name_display; ?>";       
        let zegocloud_create_room_user_id="<?php echo get_current_user_id(); ?>"; 
        let custom_ajax_action_url="<?php echo $custom_ajax_action_url; ?>";
    </script>
    <?php
}

//get video call room id 
//prevent other user from accessing to other video call and init video id for frontend
add_action('wp_head','get_video_call_room_id',1);

function get_video_call_room_id()
{
    if(is_page_template('custom-video-call-room.php'))
    {              
        $video_call_room_id=$_GET['videoroomid'];              
        $isValid_video_room_id=get_booking_item_by_video_call_room_id($video_call_room_id);        
        if($isValid_video_room_id['exist']==false || (get_current_user_id() != $isValid_video_room_id['booking_trainee_id'] && 
        get_current_user_id() != $isValid_video_room_id['booking_coach_id']))
        {                        
            wp_redirect(site_url());
            exit();
        }   
     
        ?>
        <script>
            let video_call_room_id="<?php echo $video_call_room_id; ?>";
        </script>
        <?php
    }
}


function get_booking_item_by_video_call_room_id($room_id='randomstring')
{      
    $search_booking_args=array('post_type' => 'custom_booking',
    'posts_per_page' => -1,     
    'post_status' =>'publish',        
    'meta_query'  => array(
            array(
                'key'=>'booking_video_call_room_id',
                'value'=>$room_id,
                'compare' => '=',
            )
    ),  
    );
    $search_booking=new WP_Query($search_booking_args);

    if($search_booking->have_posts())
    {
     
       while($search_booking->have_posts())
       {
           $search_booking->the_post();
           $search_booking_id=get_the_ID();         
       }
       $booking_item_info['exist']=true;
       $booking_item_info['booking_time']=get_post_meta($search_booking_id,'booking_time',true);
       $booking_item_info['booking_date']=get_post_meta($search_booking_id,'booking_date',true);
       $booking_item_info['booking_trainee_id']=get_post_meta($search_booking_id,'trainee_id',true);
       $booking_item_info['booking_coach_id']=get_post_meta($search_booking_id,'coach_id',true);
       $booking_item_info['booking_status']=get_post_meta($search_booking_id,'booking_status',true);
    }
    else
    {
        $booking_item_info['exist']=false;
    }
    wp_reset_postdata();  
     
    return $booking_item_info;
}

