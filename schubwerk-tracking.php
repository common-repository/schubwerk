<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://schubwerk.de
 * @since             2.0.0
 * @package           Schubwerk_Tracking
 *
 * @wordpress-plugin
 * Plugin Name:       schubwerk Tracking
 * Plugin URI:        https://schubwerk.de/schubwerk-for-wordpress
 * Description:       Official schubwerk tracking plugin for WordPress.
 * Version:           2.1.0
 * Author:            schubwerk GmbH
 * Author URI:        https://schubwerk.de
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       schubwerk-tracking
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at versionv1.0.14 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SCHUBWERK_TRACKING_VERSION', '2.1.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-schubwerk-tracking-activator.php
 */
function activate_schubwerk_tracking() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-schubwerk-tracking-activator.php';
	Schubwerk_Tracking_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-schubwerk-tracking-deactivator.php
 */
function deactivate_schubwerk_tracking() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-schubwerk-tracking-deactivator.php';
	Schubwerk_Tracking_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_schubwerk_tracking' );
register_deactivation_hook( __FILE__, 'deactivate_schubwerk_tracking' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-schubwerk-tracking.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since   v1.0.14
 */
function run_schubwerk_tracking() {

	$plugin = new Schubwerk_Tracking();
	$plugin->run();

}
run_schubwerk_tracking();
