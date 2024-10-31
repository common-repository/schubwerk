<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://schubwerk.de
 * @since      v1.0.14
 *
 * @package    Schubwerk_Tracking
 * @subpackage Schubwerk_Tracking/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      v1.0.14
 * @package    Schubwerk_Tracking
 * @subpackage Schubwerk_Tracking/includes
 * @author     schubwerk GmbH <support@schubwerk.de>
 */
class Schubwerk_Tracking_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    v1.0.14
	 */
	public static function deactivate() {
        delete_option( 'schubwerk-tracking-options');
        delete_option( 'schubwerk-tracking-deferred-admin_notices');
	}

}
