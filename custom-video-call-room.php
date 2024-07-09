<?php
/*
Template Name: Custom Video Call Room Page
*/
?>
<?php
get_header();
?>
<?php 
 $video_call_room_id=$_GET['videoroomid'];
 $valid_video_call=get_booking_item_by_video_call_room_id($video_call_room_id);

 $booking_time=$valid_video_call['booking_time'];
 $booking_date=$valid_video_call['booking_date'];

 $booking_date_obj = DateTime::createFromFormat('Y-m-d', $booking_date);
 $booking_time_obj=DateTime::createFromFormat('h:i A', $booking_time);
?>

<?php if ($valid_video_call['booking_status']=='overdue' || $valid_video_call['booking_status']=='inprogress'): ?>
    <div class="container video-call-container-wrapper" id="video-container">
 
    </div>
<?php else: ?>
    <div class="container not-match-time-alert">
            The video call could only be started when the time arrives.
            <p><strong><?php echo $booking_date_obj->format('j F Y').' | '.$booking_time; ?></strong></p>           
    </div>
<?php endif; ?>


<?php
get_footer();