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

 $booking_time_obj=DateTime::createFromFormat('h:i A', $booking_time);
 $booking_time_obj->modify('-30 minutes');

 
 $current_time_obj =DateTime::createFromFormat('h:i A', date('h:i A', current_time( 'timestamp' )));
 
 if($booking_time_obj <= $current_time_obj)
 {
    $is_good_time=true;
 }
 else
 {
    $is_good_time=false;
 }


$booking_date_obj = DateTime::createFromFormat('Y-m-d', $booking_date);
$current_date_obj= DateTime::createFromFormat('Y-m-d', date('Y-m-d'));

if($booking_date_obj <= $current_date_obj)
{
    $is_good_date=true;
    
    if($booking_date_obj < $current_date_obj)
    {
        $is_skip_time=true;
    }
}
else
{
    $is_good_date=false;
    $is_skip_time=false;
}


?>

<?php if (($is_good_date==true && $is_good_time==true) || $is_skip_time==true): ?>
    <div class="container video-call-container-wrapper" id="video-container">
 
    </div>
<?php else: ?>
    <div class="container not-match-time-alert">
            The video call could be only started before 30 minutes of the beginning time.
            <p><strong><?php echo $booking_date_obj->format('j F Y').' | '.$booking_time; ?></strong></p>           
    </div>
<?php endif; ?>


<?php
get_footer();