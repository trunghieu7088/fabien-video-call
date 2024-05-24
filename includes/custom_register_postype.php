<?php
add_action('init','register_custom_service_postype');

function register_custom_service_postype()
{
    $labels = array(
        'name'               => _x( 'Video call service', 'post type general name', 'textdomain' ),
        'singular_name'      => _x( 'Video call service', 'post type singular name', 'textdomain' ),
        'menu_name'          => _x( 'Video call services', 'admin menu', 'textdomain' ),
        'name_admin_bar'     => _x( 'Video call service', 'add new on admin bar', 'textdomain' ),
        'add_new'            => _x( 'Add New', 'Video call service', 'textdomain' ),
        'add_new_item'       => __( 'Add New Video call service', 'textdomain' ),
        'new_item'           => __( 'New Video call service', 'textdomain' ),
        'edit_item'          => __( 'Edit Video call service', 'textdomain' ),
        'view_item'          => __( 'View Video call service', 'textdomain' ),
        'all_items'          => __( 'All Video call service', 'textdomain' ),
        'search_items'       => __( 'Search Video call service', 'textdomain' ),
        'not_found'          => __( 'No Video call services found', 'textdomain' ),
        'not_found_in_trash' => __( 'No Video call services found in trash', 'textdomain' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'videoservice' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields' ), // Adjust as needed        
    );

    register_post_type( 'videoservice', $args );
}

add_action('init','add_video_call_service_category');
  function add_video_call_service_category()
  {
      $service_category_args = array(
          'hierarchical'      => false,
          'labels'            => array(
              'name'                       => _x('Service Category', 'taxonomy general name'),
              'singular_name'              => _x('Service Category', 'taxonomy singular name'),
              'search_items'               => __('Search Service Category'),
              'popular_items'              => __('Popular Service Category'),
              'all_items'                  => __('All Service Category'),
              'parent_item'                => null,
              'parent_item_colon'          => null,
              'edit_item'                  => __('Edit Service Category'),
              'update_item'                => __('Update Service Category'),
              'add_new_item'               => __('Add New Service Category'),
              'new_item_name'              => __('New Service Category'),                
              'add_or_remove_items'        => __('Add or remove Service Category'),
              'choose_from_most_used'      => __('Choose from the most used Service Category'),
              'menu_name'                  => __('Service Category'),
          ),
          'rewrite'           => array('slug' => 'service-category','hierarchical'=>false),
          'show_admin_column' => true,
          'query_var'         => true,
      );
          
      register_taxonomy('service_category', array('videoservice'), $service_category_args);
  }
  
 