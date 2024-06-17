<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;


add_action('carbon_fields_register_fields', 'custom_video_call_settings', 999, 0);

function custom_video_call_settings()
{
    Container::make('theme_options', __('Custom Video Call Settings', 'crb'))
    ->set_icon('dashicons-admin-generic')
    ->set_page_menu_title('Custom Video Call Settings')
    ->set_page_menu_position(6)
    ->add_tab('Custom Stripe API Key', array(
        Field::make('text', 'custom_video_call_stripe_pk', __('Stripe public key'))->set_default_value('none'),
        Field::make('text', 'custom_video_call_stripe_sk', __('Stripe secret key'))->set_default_value('none'),
        Field::make('text', 'custom_video_stripe_client_id', __('Stripe client ID'))->set_default_value('none'),
    ))
    ->add_tab('Currency Settings', array(
        Field::make('text', 'custom_video_call_stripe_currency_code', __('Currency Code'))->set_default_value('usd'),
        Field::make('text', 'custom_video_call_stripe_currency_sign', __('Currency Sign'))->set_default_value('$'),      
    ))
    ->add_tab('General Settings', array(
        Field::make( 'file', 'custom_video_call_default_cover_img_service', __( 'Default Image for service' ) )->set_type( array('image' ) )->set_value_type( 'url' ),
    
    ))
    ->add_tab('ZegoCloud Video Call', array(
        Field::make('text', 'zegocloud_appid', __('App ID'))->set_default_value('none'),
        Field::make('text', 'zegocloud_serversecret', __('Server Secret'))->set_default_value('none'),
    )); 


        


}


