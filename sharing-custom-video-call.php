<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://sharing-horses-dogs-cats.com
 * @since             1.0.0
 * @package           Sharing_Custom_Video_Call
 *
 * @wordpress-plugin
 * Plugin Name:       Sharing Custom Video Call
 * Plugin URI:        https://sharing-horses-dogs-cats.com
 * Description:       An awesome video to make an appointment and video call
 * Version:           1.0.0
 * Author:            Thien Vu
 * Author URI:        https://sharing-horses-dogs-cats.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sharing-custom-video-call
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SHARING_CUSTOM_VIDEO_CALL_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sharing-custom-video-call-activator.php
 */
function activate_sharing_custom_video_call() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sharing-custom-video-call-activator.php';
	Sharing_Custom_Video_Call_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sharing-custom-video-call-deactivator.php
 */
function deactivate_sharing_custom_video_call() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sharing-custom-video-call-deactivator.php';
	Sharing_Custom_Video_Call_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sharing_custom_video_call' );
register_deactivation_hook( __FILE__, 'deactivate_sharing_custom_video_call' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sharing-custom-video-call.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sharing_custom_video_call() {

	$plugin = new Sharing_Custom_Video_Call();
	$plugin->run();

}
run_sharing_custom_video_call();
