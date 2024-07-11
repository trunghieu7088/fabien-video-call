<?php
get_header();

?>
<?php 
global $post;
$current_service=convert_service_for_display($post);
$is_trainee=determine_role_by_id(get_current_user_id(),'trainee');
$textManager = TextManager::getInstance();
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
            
            <?php if(is_user_logged_in() && get_current_user_id()!== $current_service['service_owner_ID'] && $is_trainee==true):?>
            <div class="custom-video-service-calendar">
                <div id="custom-video-calendar">

                </div>
            </div>

            
            <div class="custom-video-service-checkout-form-wrapper">
                <form id="custom-video-service-checkout-form" name="custom-video-service-checkout-form" action="">                    
                    <p class="video-call-checkout-title"><?php echo $textManager->getText('book_an_appointment');?></p>
                    <input type="hidden" name="action" id="action" value="custom_video_service_order_checkout">                                        
                    <input type="hidden" name="checkout_service_nonce" id="checkout_service_nonce" value="<?php echo wp_create_nonce('checkout_service_nonce'); ?>">
                    <input type="hidden" name="service_price" id="service_price" value="<?php echo (int)$current_service['price']; ?>">  
                    <input type="hidden" name="service_currency" id="service_currency" value="<?php echo $current_service['currency_code']; ?>">  
                    <input type="hidden" name="service_currency_sign" id="service_currency_sign" value="<?php echo $current_service['currency_sign']; ?>">  
                    <input type="hidden" name="selected_time" id="selected_time">                                      
                    <input type="hidden" name="coach_id" id="coach_id" value="<?php echo $current_service['service_owner_ID']; ?>">                                                          
                    <input type="hidden" name="service_id" id="service_id" value="<?php echo $current_service['ID']; ?>">
                    <input type="text" id="selected_date" name="selected_date" placeholder="<?php echo $textManager->getText('use_calendar_text');?>" class="info-checkout-field" required="required">
                    <input id="selected-time-area" name="selected-time-area"  class="info-checkout-field">
                    <input type="hidden" name="booking_type" id="booking_type" value="<?php echo $current_service['meeting_type_logic_value']; ?>">
                    <input type="hidden" name="booking_location" id="booking_location" value="<?php echo $current_service['meettype_location']; ?>">
                    <input type="text" class="info-checkout-field" name="booking_note" id="booking_note" placeholder="<?php echo $textManager->getText('notice_text_placeholder');?>" required="required">
                    <input type="text" class="info-checkout-field" name="billing_name" id="billing_name" placeholder="<?php echo $textManager->getText('billing_name_placeholder');?>" required="required">
                    <div id="custom-card-element" class="custom-form-card-element">
                        <!-- A Stripe Element will be inserted here. -->
                    </div>
                    <div id="card-errors" role="alert"></div>                    
                    <button type="submit" id="custom-video-service-checkout-btn" class="custom-video-service-checkout-btn"><?php echo $textManager->getText('order_service_btn_label');?> <?php echo $current_service['price'].'$'; ?></button>
                </form>
            </div>
            <?php else: ?>
                <div class="custom-video-service-checkout-form-wrapper">
                    <p>Please login as trainee can book an appointment</p>
                </div>
            <?php endif; ?>

        </div>

        <div class="col-md-4 col-lg-4 col-sm-12 single-video-right-section">
            <div class="custom-video-service-detail">
                <p class="custom-video-service-price"><?php echo $current_service['price'].$current_service['currency_sign']; ?></p>
                <p class="info-item"><i class="fa fa-calendar"></i> <?php echo $textManager->getText('published_list_label').' '.$current_service['human_readable_time']; ?></p>
                <p class="info-item"><i class="fa fa-list"></i> <?php echo $current_service['category']; ?></p>
                <p class="info-item"><i class="fa fa-user"></i> <a href="<?php echo $current_service['service_owner_url']; ?>"><?php echo $current_service['service_owner_name']; ?></a></p>
                <p class="info-item"><i class="fa fa-clock"></i> <?php echo $textManager->getText('duration_list_label').' '.$current_service['duration'].' minutes'; ?></p>
                <p class="info-item"><i class="fa fa-message"></i> <?php echo $current_service['meeting_type']; ?></p>
                <?php if($current_service['meeting_type_logic_value']=='face-to-face'): ?>
                    <p class="info-item"><i class="fa fa-map-marker"></i> <?php echo $current_service['meettype_location']; ?></p>
                <?php endif; ?>

                <p class="info-item">
                <?php if(get_current_user_id()==$current_service['service_owner_ID']): ?>
                        <button class="publish-unpublish-btn custom_btn_publish" data-service-id="<?php echo $current_service['ID']; ?>">
                            <?php if($current_service['service_status']=='publish'): ?>
                                <i class="fa fa-eye-slash"></i> Unpublish service  
                            <?php else: ?>
                                <i class="fa fa-eye"></i> Publish service
                            <?php endif; ?>
                        </button> 
                    <?php endif; ?> 
                </p>        
            </div>
        </div>


    </div>

</div>
<div id="calendartest"></div>
<?php
get_footer();
?>