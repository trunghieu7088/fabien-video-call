<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://sharing-horses-dogs-cats.com
 * @since      1.0.0
 *
 * @package    Sharing_Custom_Video_Call
 * @subpackage Sharing_Custom_Video_Call/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Sharing_Custom_Video_Call
 * @subpackage Sharing_Custom_Video_Call/includes
 * @author     Thien Vu <thienvu3666@hotmail.com>
 */
class Sharing_Custom_Video_Call_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'sharing-custom-video-call',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
