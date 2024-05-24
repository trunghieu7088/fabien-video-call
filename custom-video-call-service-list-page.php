<?php
/*
Template Name: Custom Video Call Service List page
*/
?>
<?php
get_header();
?>
<?php 
$current_page = get_query_var('paged') ? get_query_var('paged') : 1;    
$service_list=get_all_video_services($current_page);
?>
    <div class="container service-list-wrapper">

        <div class="row">
            <?php if(isset($service_list) && !empty($service_list['service_list'])): ?>
                <?php foreach($service_list['service_list'] as $service_item): ?>
                        
                    <div class="col-md-12 col-sm-12 col-xs-12 custom-service-item">
                        <div class="row">

                            <div class="col-lg-4 col-md-4 col-sm-12 custom-service-image-container">
                                <a href="<?php echo $service_item['service_slug']; ?>">
                                    <img src="<?php echo $service_item['attached_image_url']; ?>">
                                </a>
                            </div>

                            <div class="col-lg-8 col-md-8 col-sm-12 custom-service-content-container">
                                <p class="custom-service-list-item-title">
                                    <a href="<?php echo $service_item['service_slug']; ?>">
                                        <?php echo $service_item['title'].' | '.$service_item['price'].'$'; ?>
                                    </a>
                                </p>

                                <div class="custom-service-list-item-info">
                                    <span><i class="fa fa-calendar"></i> Published <?php echo $service_item['human_readable_time'];?></span>
                                    <span><i class="fa fa-list"></i> <?php echo $service_item['category'];?></span>
                                    <span><i class="fa fa-clock"></i> Duration <?php echo $service_item['duration'];?> mins</span>
                                </div>

                                <div class="custom-service-list-item-description">
                                    <?php echo $service_item['description']; ?>
                                </div>

                                <div class="custom-service-list-item-author">
                                    <a href="<?php echo $service_item['service_owner_url'];?>">
                                        <?php echo $service_item['service_owner_name']; ?>
                                        <img src="<?php echo $service_item['avatar']; ?>">
                                    </a>
                                </div>

                            </div>

                        </div>
                    </div>

                <?php endforeach; ?>
            <?php endif; ?>
          

        </div>

    </div>

<?php
get_footer();
?>