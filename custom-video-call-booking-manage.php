<?php
/*
Template Name: Custom Video Call Booking Management Page
*/
?>
<?php
get_header();
?>
<?php 
$custom_user_info=wp_get_current_user();
if ( in_array( 'coach', $custom_user_info->roles, true )) 
{
    $owner_type='coach';    
}
else
{
    $owner_type='trainee';
}
$current_page = get_query_var('paged') ? get_query_var('paged') : 1;    
$booking_list=get_all_bookings($current_page,get_current_user_id(),$owner_type);

?>
<div class="container all-booking-wrapper">
    <p class="all-booking-title">My Booking</p>
    <div class="row custom-video-booking-item-list">
    <?php if(isset($booking_list) && !empty($booking_list['booking_list'])): ?>
        <?php foreach($booking_list['booking_list'] as $booking_item): ?>
        <!-- booking item -->
        <div class="col-sm-12 col-md-12 col-lg-12 custom-video-booking-item <?php if($booking_item['booking_status']=='upcoming') echo 'custom-upcoming-border'; if($booking_item['booking_status']=='completed') echo 'custom-completed-border'; ?>">
            
            <div class="row custom-video-booking-item-content">

                <!-- left column -->
                <div class="col-sm-6 col-md-3 col-lg-3 custom-video-booking-item-datetime custom-video-booking-item-column">
                    
                    <div class="item-column">
                        <i class="fa fa-calendar"></i> 
                        <strong><?php echo $booking_item['human_readable_date']; ?></strong>
                    </div>
                    
                    <div class="item-column">
                        <i class="fa fa-clock"></i> 
                        <?php echo $booking_item['booking_time']; ?>
                    </div>
                    
                    <div class="item-column <?php if($booking_item['booking_status']=='upcoming') echo 'custom-upcoming-booking'; if($booking_item['booking_status']=='completed') echo 'custom-completed-booking'; ?>">
                        <i class="fa fa-dot-circle"></i> 
                        <?php echo $booking_item['booking_status']; ?>
                    </div>                    

                    <div class="item-column">
                        <i class="fa fa-shopping-cart"></i> 
                        <strong><?php echo $booking_item['booking_amount'].$booking_item['booking_currency_sign']; ?></strong>
                    </div>


                    <?php if($booking_item['booking_status']=='upcoming'): ?>
                    <div class="item-column custom-actions">
                       
                            <a class="action-link" href="<?php echo site_url('video-call').'/?videoroomid='.$booking_item['booking_room_id'] ?>"><i class="fa fa-right-to-bracket"></i> Join</a>
                            <a class="action-link" href="#"><i class="fa fa-circle-xmark"></i> Cancel</a>
                       
                    </div>
                    <?php endif; ?>

                </div>

                <!-- center column -->
                <div class="col-sm-6 col-md-3 col-lg-3 custom-video-booking-item-author custom-video-booking-item-column">
                    <div class="item-column booking-avatar">
                        <img src="http://fabien.et/wp-content/uploads/2024/05/sample_avatar.jpg">
                    </div>
                    <div class="item-column">
                        <?php
                        if(get_current_user_id()==(int)$booking_item['trainee_id'])
                        {
                            $display_name='<span style="margin-right:5px;">Coach:</span> '.'<strong>'.$booking_item['coach_name'].'</strong>';
                        }
                        if(get_current_user_id()==(int)$booking_item['coach_id'])
                        {
                            $display_name='<span style="margin-right:5px;">Trainee:</span> '.'<strong>'.$booking_item['trainee_name'].'</strong>';
                        }
                        echo $display_name;
                        ?>
                    </div>

                </div>

                <!-- right column -->
                <div class="col-sm-12 col-md-6 col-lg-6 custom-video-booking-item-service custom-video-booking-item-column">
                    <div class="item-column service-title">
                        <?php echo $booking_item['title']; ?>
                    </div>        
                    <div class="item-column service-description">
                        <?php echo wp_trim_words($booking_item['description'],30,'..'); ?>
                    </div>    
                </div>


            </div>
            
        </div>
        <!-- end booking item -->
         <?php endforeach; ?>
    <?php endif; ?>
    </div>
</div>
<?php
get_footer();