<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://schubwerk.de
 * @since      v1.0.14
 *
 * @package    Schubwerk_Tracking
 * @subpackage Schubwerk_Tracking/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      v1.0.14
 * @package    Schubwerk_Tracking
 * @subpackage Schubwerk_Tracking/includes
 * @author     schubwerk GmbH <support@schubwerk.de>
 */
class Schubwerk_Tracking_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    v1.0.14
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'schubwerk-tracking',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
