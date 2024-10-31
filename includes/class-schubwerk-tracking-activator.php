<?php

/**
 * Fired during plugin activation
 *
 * @link       https://schubwerk.de
 * @since      v1.0.14
 *
 * @package    Schubwerk_Tracking
 * @subpackage Schubwerk_Tracking/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      v1.0.14
 * @package    Schubwerk_Tracking
 * @subpackage Schubwerk_Tracking/includes
 * @author     schubwerk GmbH <support@schubwerk.de>
 */
class Schubwerk_Tracking_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    v1.0.14
     */
    public static function activate() {
        flush_rewrite_rules();
    }

}
