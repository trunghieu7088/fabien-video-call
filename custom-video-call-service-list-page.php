<?php
/*
Template Name: Custom Video Call Service List page
*/
?>
<?php
get_header();
$textManager = TextManager::getInstance();
?>
<?php 

$search_string = isset($_GET['search']) ? $_GET['search'] : '';

$service_category_chosen = isset($_GET['category']) ? $_GET['category'] : '';

$date_sort = isset($_GET['date']) ? $_GET['date'] : '';

$price_sort = isset($_GET['price']) ? $_GET['price'] : '';

$meettype_sort = isset($_GET['meettype']) ? $_GET['meettype'] : '';

$only_myservice= isset($_GET['myservice']) ? $_GET['myservice'] : '';

$current_page = get_query_var('paged') ? get_query_var('paged') : 1;    
$service_list=get_all_video_services($current_page, $search_string,$service_category_chosen,$date_sort,$price_sort,$meettype_sort,$only_myservice);
$service_category_list=get_all_service_category();
?>
    <div class="container service-list-wrapper">
        <div class="all-services-title">
            <span class="all-service-text-title"><?php echo $textManager->getText('all_service_label'); ?></span>
            <div class="my-service-container">                
                <?php if(determine_role_by_id(get_current_user_id(),'coach')): ?>
                    <label class="custom-switch">
                        <input type="checkbox" id="only_my_service" name="only_my_service" value="myservice" <?php if($only_myservice=='yes') echo 'checked'; ?>> 
                        <span class="switch-slider round"></span>
                    </label>                
                    <span class="myservice-text"><?php echo $textManager->getText('only_my_service'); ?></span>   
                <?php endif; ?>             
            </div>
        </div>
        <div class="row service-custom-filters-container">           
            <div class="col-md-3 col-lg-3 col-sm-12 custom-filter-option custom-filter-search">
                <i class="fa fa-search"></i> <input type="text" id="search_string" name="search_string" placeholder="<?php echo $textManager->getText('search_service_label'); ?>" value="<?php echo $search_string; ?>">
            </div>
            <div class="col-md-4 col-lg-4 col-sm-12 custom-filter-option custom-filter-category">

                <select id="service_category_selector" name="service_category_selector" class="custom-filter-service" placeholder="<?php echo $textManager->getText('select_category_list_label'); ?>" autocomplete="off">
                        <option value=""><?php echo $textManager->getText('select_category_list_label'); ?></option>                        
                        <?php foreach($service_category_list as $service_category): ?>
                            <option <?php if($service_category->slug==$service_category_chosen) echo 'selected'; ?> value="<?php echo $service_category->slug ?>"><?php echo $service_category->name;?></option>
                        <?php endforeach; ?>
                </select>
                
            </div>

            <div class="col-md-2 col-lg-2 col-sm-12 custom-filter-option custom-filter-date">                
                <select id="service_sort_by_time" name="service_sort_by_time" class="custom-filter-service" placeholder="Sort by time" autocomplete="off">
                        <option <?php if($date_sort=='latest') echo 'selected'; ?> value="latest"><?php echo $textManager->getText('latest_label'); ?></option>           
                        <option <?php if($date_sort=='oldest') echo 'selected'; ?> value="oldest"><?php echo $textManager->getText('oldest_label'); ?></option>           
                </select>    
                          
            </div>

            <div class="col-md-2 col-lg-2 col-sm-12 custom-filter-option custom-filter-price">                
                <!-- <select id="service_sort_by_price" name="service_sort_by_price" class="custom-filter-service" placeholder="Sort by price" autocomplete="off">
                        <option value="">Sort by price</option>
                        <option <?php if($price_sort=='hightolow') echo 'selected'; ?> value="hightolow">Price: high to low</option>           
                        <option <?php if($price_sort=='lowtohigh') echo 'selected'; ?> value="lowtohigh">Price: low to high</option>           
                </select>  -->  
                <select id="service_sort_by_meet_type" name="service_sort_by_meet_type" class="custom-filter-service" placeholder="<?php echo $textManager->getText('sort_by_meettype_label'); ?>" autocomplete="off">
                        <option value=""><?php echo $textManager->getText('sort_by_meettype_label'); ?></option>
                        <option <?php if($meettype_sort=='face-to-face') echo 'selected'; ?> value="face-to-face"><?php echo $textManager->getText('face_to_face_list_label'); ?></option>           
                        <option <?php if($meettype_sort=='online-meeting') echo 'selected'; ?> value="online-meeting"><?php echo $textManager->getText('online_meeting_list_label'); ?></option>           
                </select> 
                         
            </div>

            <div class="col-md-1 col-lg-1 col-sm-12 custom-filter-option custom-filter-search-btn-container">                                
                    <button type="button" id="submit-search-service" class="custom-search-service-btn"><i class="fa fa-search"></i> <?php echo $textManager->getText('search_go_button_label'); ?></button>                
                    <input type="hidden" value="<?php echo site_url('custom-service-list'); ?>" name="search_link" id="search_link">
            </div>
            

        </div>
        <div class="row">
            <?php if(isset($service_list) && !empty($service_list['service_list'])): ?>
                <?php foreach($service_list['service_list'] as $service_item): ?>
                        
                    <div class="col-md-12 col-sm-12 col-xs-12 custom-service-item">
                        <div class="row">

                            <div class="col-lg-4 col-md-4 col-sm-12 custom-service-image-container">
                                <a href="<?php echo $service_item['service_single_link']; ?>">
                                    <img src="<?php echo $service_item['attached_image_url']; ?>">
                                </a>
                            </div>

                            <div class="col-lg-8 col-md-8 col-sm-12 custom-service-content-container">
                                <p class="custom-service-list-item-title">
                                    <a href="<?php echo $service_item['service_single_link']; ?>">
                                        <?php echo $service_item['title'].' | '.'<strong>'.$service_item['price'].$service_item['currency_sign'].'</strong>'; ?>
                                    </a>
                                    <?php if($service_item['service_owner_ID'] == get_current_user_id()): ?>
                                    <button class="publish-unpublish-btn custom_btn_publish" data-service-id="<?php echo $service_item['ID']; ?>">
                                        <?php if($service_item['service_status']=='publish'): ?>
                                            <i class="fa fa-eye-slash"></i> <?php echo $textManager->getText('unpublish_service_btn'); ?>
                                        <?php else: ?>
                                            <i class="fa fa-eye"></i> <?php echo $textManager->getText('publish_service_btn'); ?>
                                        <?php endif; ?>
                                    </button>
                                    <?php endif; ?>
                                </p>

                                <div class="custom-service-list-item-info">
                                    <span><i class="fa fa-calendar"></i> <?php echo $textManager->getText('published_list_label'); ?> <?php echo $service_item['human_readable_time'];?></span>
                                    <span><i class="fa fa-list"></i> <?php echo $service_item['category'];?></span>
                                    <span><i class="fa fa-clock"></i> <?php echo $textManager->getText('duration_list_label'); ?> <?php echo $service_item['duration'];?> mins</span>
                                    <span><i class="fa fa-message"></i> <?php echo $service_item['meeting_type'];?></span>
                                    <?php if($service_item['meeting_type_logic_value']=='face-to-face'): ?>
                                        <span><i class="fa fa-map-marker"></i> <?php echo $service_item['meettype_location'];?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="custom-service-list-item-description">
                                    <?php echo $service_item['short_description']; ?>
                                </div>

                                <div class="custom-service-list-item-author">                                
                                    <a target="_blank" href="<?php echo $service_item['service_owner_url'];?>">
                                        <?php echo $service_item['service_owner_name']; ?>
                                        <img src="<?php echo $service_item['avatar']; ?>">
                                    </a>
                                </div>

                            </div>

                        </div>
                    </div>

                <?php endforeach; ?>
            
          

        </div>

        <div class="row service-list-pagination-wrapper">
            <ul class="custom-service-pagination">
                <?php 
                    $big = 999999999;
                    if(isset($service_list) && is_array($service_list['service_list']) && !empty($service_list['service_list']))
                    {                      

                        $pagination_list= paginate_links( array(
                            //'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                            'base' => site_url('custom-service-list').'/page/%#%/',                            
                            'total'    => $service_list['max_num_pages'],
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

<?php
get_footer();
?>