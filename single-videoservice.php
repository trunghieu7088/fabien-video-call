<?php
get_header();

?>
<?php 
global $post;
$current_service=convert_service_for_display($post);
?>
<div class="container single-video-service-wrapper">
    <div class="row">
        <div class="col-md-8 col-lg-8 col-sm-12 single-video-left-section">
            <div class="custom-video-service-title">
               <p><?php echo $current_service['title'] ?></p>
            </div>

            <div class="custom-video-service-image">
                <img class="custom-service-image" src="<?php echo $current_service['attached_image_url']; ?>">
            </div>

            <div class="custom-video-service-description">
                <?php echo $current_service['description']; ?>
            </div>
            
            <div class="custom-video-service-calendar">
                <div id="custom-video-calendar">

                </div>
            </div>


            <div class="custom-video-service-checkout-form-wrapper">
                <form id="custom-video-service-checkout-form" name="custom-video-service-checkout-form" action="">                    
                    <p class="video-call-checkout-title">Book a Video Call</p>
                    <input type="hidden" name="action" id="action" value="custom_video_service_order_checkout">                                        
                    <input type="hidden" name="checkout_service_nonce" id="checkout_service_nonce" value="<?php echo wp_create_nonce('checkout_service_nonce'); ?>">
                    <input type="hidden" name="service_price" id="service_price" value="<?php echo (int)$current_service['price']; ?>">  
                    <input type="hidden" name="service_currency" id="service_currency" value="<?php echo $current_service['currency_code']; ?>">  
                    <input type="hidden" name="service_currency_sign" id="service_currency_sign" value="<?php echo $current_service['currency_sign']; ?>">  
                    <input type="hidden" name="selected_time" id="selected_time">                                      
                    <input type="hidden" name="coach_id" id="coach_id" value="<?php echo $current_service['service_owner_ID']; ?>">                                                          
                    <input type="hidden" name="service_id" id="service_id" value="<?php echo $current_service['ID']; ?>">
                    <input type="text" id="selected_date" name="selected_date" placeholder="Use Calendar to pick date (yyyy-mm-dd)" class="info-checkout-field" required="required">
                    <input id="selected-time-area" name="selected-time-area"  class="info-checkout-field">
                    <input type="hidden" name="booking_type" id="booking_type" value="<?php echo $current_service['meeting_type_logic_value']; ?>">
                    <input type="hidden" name="booking_location" id="booking_location" value="<?php echo $current_service['meettype_location']; ?>">
                    <input type="text" class="info-checkout-field" name="booking_note" id="booking_note" placeholder="contact methods or things you want the coach to notice" required="required">
                    <input type="text" class="info-checkout-field" name="billing_name" id="billing_name" placeholder="Billing Name" required="required">
                    <div id="custom-card-element" class="custom-form-card-element">
                        <!-- A Stripe Element will be inserted here. -->
                    </div>
                    <div id="card-errors" role="alert"></div>                    
                    <button type="submit" id="custom-video-service-checkout-btn" class="custom-video-service-checkout-btn">Order Service <?php echo $current_service['price'].'$'; ?></button>
                </form>
            </div>

        </div>

        <div class="col-md-4 col-lg-4 col-sm-12 single-video-right-section">
            <div class="custom-video-service-detail">
                <p class="custom-video-service-price"><?php echo $current_service['price'].$current_service['currency_sign']; ?></p>
                <p class="info-item"><i class="fa fa-calendar"></i> <?php echo 'Published '.$current_service['human_readable_time']; ?></p>
                <p class="info-item"><i class="fa fa-list"></i> <?php echo $current_service['category']; ?></p>
                <p class="info-item"><i class="fa fa-user"></i> <a href="<?php echo $current_service['service_owner_url']; ?>"><?php echo $current_service['service_owner_name']; ?></a></p>
                <p class="info-item"><i class="fa fa-clock"></i> <?php echo 'Duration: '.$current_service['duration'].' minutes'; ?></p>
                <p class="info-item"><i class="fa fa-message"></i> <?php echo $current_service['meeting_type']; ?></p>
                <?php if($current_service['meeting_type']=='face-to-face'): ?>
                    <p class="info-item"><i class="fa fa-map-marker"></i> <?php echo $current_service['meettype_location']; ?></p>
                <?php endif; ?>
            </div>
        </div>


    </div>

</div>
<div id="calendartest"></div>
<?php
get_footer();
?>