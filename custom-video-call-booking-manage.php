<?php
/*
Template Name: Custom Video Call Booking Management Page
*/
?>
<?php
if(!is_user_logged_in())
{
    wp_redirect(site_url());
}
get_header();

?>
<?php 
$custom_user_info=wp_get_current_user();

$filter_status=isset($_GET['status']) ? $_GET['status'] : '';

$filter_order=isset($_GET['order']) ? $_GET['order'] : '';

$filter_type = isset($_GET['type']) ? $_GET['type'] : '';

$filter_condition=array('order'=>$filter_order,'status'=>$filter_status,'type'=>$filter_type);

if ( in_array( 'coach', $custom_user_info->roles, true )) 
{
    $owner_type='coach';    
}
else
{
    $owner_type='trainee';
}
$current_page = get_query_var('paged') ? get_query_var('paged') : 1;    
$booking_list=get_all_bookings($current_page,get_current_user_id(),$owner_type,$filter_condition);
$status_collection=init_booking_status();
?>
<div class="container all-booking-wrapper">
    <p class="all-booking-title">My Booking</p>
    <div class="booking-filters-area">
        <input type="hidden" name="booking-filter-base-link" id="booking-filter-base-link" value="<?php echo site_url('custom-manage-booking'); ?>">        
        <div class="booking-filter-container">
            <span class="booking-filter-title">Status</span>
            <select class="booking-option-filter" id="booking_status_filter" name="booking_status_filter">                                
                
                <?php foreach($status_collection as $status_key => $status_value): ?>
                    <option <?php if($filter_status==$status_key) echo 'selected'; ?> value="<?php echo $status_key; ?>"><?php echo $status_value; ?></option>  
                <?php endforeach; ?>

            </select>
        </div>    

        <div class="booking-filter-container">
            <span class="booking-filter-title">Date</span>
            <select class="booking-option-filter" id="booking_sort_filter" name="booking_sort_filter">           
                <option <?php if($filter_order=='asc') echo 'selected'; ?> value="asc">Oldest</option>
                <option <?php if($filter_order=='desc') echo 'selected'; ?> value="desc">Latest</option>            
            </select>
        </div>

        <div class="booking-filter-container">
            <span class="booking-filter-title">Meet type</span>
            <select id="booking_sort_meettype" name="booking_sort_meettype" class="booking-option-filter">
                        <option value="all">All</option>
                        <option <?php if($filter_type=='face-to-face') echo 'selected'; ?> value="face-to-face">face-to-face</option>           
                        <option <?php if($filter_type=='online-meeting') echo 'selected'; ?> value="online-meeting">Online</option>           
                </select> 
        </div>                

        <div class="booking-filter-container">
        <span class="booking-filter-title" style="visibility:hidden;">show</span>
            <button class="booking_filter_search" id="booking_filter_search" name="booking_filter_search">
                <i class="fa fa-filter"></i> Filter
            </button>
        </div>
    </div>
    <div class="row custom-video-booking-item-list">
    <?php if(isset($booking_list) && !empty($booking_list['booking_list'])): ?>
        <?php foreach($booking_list['booking_list'] as $booking_item): ?>
            <?php
                $collection_color=set_color_base_on_status($booking_item['booking_status']); 
                $display_action_btns=handling_display_join_cancel_buttons($booking_item['booking_status'],$booking_item['booking_type'],$booking_item['booking_room_id'],$booking_item['ID']);
            
            ?>
        <!-- booking item -->
        <div class="col-sm-12 col-md-12 col-lg-12 custom-video-booking-item <?php echo $collection_color['border']; ?>">
            
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
                    
                    <div class="item-column <?php echo $collection_color['color']; ?>">
                        <i class="fa fa-dot-circle"></i> 
                        <?php echo $booking_item['booking_status']; ?>
                    </div>                    

                    <div class="item-column">
                        <i class="fa fa-shopping-cart"></i> 
                        <strong><?php echo $booking_item['booking_amount'].$booking_item['booking_currency_sign']; ?></strong>
                    </div>
                    
                    <div class="item-column custom-actions">                       
                        <?php echo $display_action_btns; ?>                            
                    </div>               

                </div>

                <!-- center column -->
                <div class="col-sm-6 col-md-3 col-lg-3 custom-video-booking-item-author custom-video-booking-item-column">
                    <div class="item-column booking-avatar">
                        <?php if(get_current_user_id()==(int)$booking_item['trainee_id']): ?>                        
                            <img src="<?php echo $booking_item['coach_avatar']; ?>">
                        <?php endif; ?>

                        <?php if(get_current_user_id()==(int)$booking_item['coach_id']): ?>                        
                            <img src="<?php echo $booking_item['trainee_avatar']; ?>">
                        <?php endif; ?>
                       
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
                       <a target="_blank" href="<?php echo $booking_item['service_url']; ?>"> <?php echo $booking_item['title']; ?></a>
                    </div>        
                    <div class="item-column service-description">                        
                        <p  style="margin:0;"><strong>Notification Message: </strong><?php echo $booking_item['booking_note']; ?></p>
                        <p><i class="fa fa-message"></i> <strong>Type:</strong> <?php echo $booking_item['booking_type']; ?></p>
                        <?php if($booking_item['booking_type']=='face-to-face'): ?>
                            <p><i class="fa fa-map-marker"></i> <strong>Location:</strong> <?php echo $booking_item['booking_location']; ?></p>
                        <?php endif; ?>
                    </div>   

                    <div class="item-column custom-actions custom-actions-right-column">                       
                        <?php echo handling_display_join_cancel_buttons('only_join_btn_'.$booking_item['booking_status'],$booking_item['booking_type'],$booking_item['booking_room_id'],$booking_item['ID']); ?>                            
                    </div>
                   
                </div>


            </div>
            
        </div>
        <!-- end booking item -->
         <?php endforeach; ?>

         <div class="row service-list-pagination-wrapper">
            <ul class="custom-service-pagination">
                <?php 
                    $big = 999999999;
                    if(isset($booking_list) && is_array($booking_list['booking_list']) && !empty($booking_list['booking_list']))
                    {                      

                        $pagination_list= paginate_links( array(
                            //'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                            'base' => site_url('custom-manage-booking').'/page/%#%/',                            
                            'total'    => $booking_list['max_num_pages'],
                            'current'  => max( 1, get_query_var( 'paged' ) ),
                            'prev_text' => 'Previous',
                            'next_text' => 'Next',
                            //'format' => '?paged=%#%',
                            //'format' => 'page=%#%',
                            //'format' => 'page/%#%/',                            
                            'type'=>'array',      
                            'add_args'=>false,                     
                        ) );
                           
                        if($pagination_list && is_array($pagination_list))
                        {
                            foreach($pagination_list as $page_item)
                            {
                                echo '<li>'.$page_item.'</li>';
                            }
                        }
                    }
                ?>
            </ul>
        </div>

    <?php endif; ?>
    </div>
</div>
<?php
get_footer();