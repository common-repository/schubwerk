<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://schubwerk.de
 * @since      v1.0.14
 *
 * @package    Schubwerk_Tracking
 * @subpackage Schubwerk_Tracking/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Schubwerk_Tracking
 * @subpackage Schubwerk_Tracking/public
 * @author     schubwerk GmbH <support@schubwerk.de>
 */
class Schubwerk_Tracking_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    v1.0.14
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    v1.0.14
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;


    /**
     * Instance of Schubwerk_Tracker.
     *
     * @since    v1.0.14
     * @access   private
     * @var      Schubwerk_Tracker    $tracker    The current version of this plugin.
     */
    private $tracker;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     * @since    v1.0.14
     */
    public function __construct($plugin_name, $version, $tracker) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->tracker = $tracker;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    v1.0.14
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Schubwerk_Tracking_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Schubwerk_Tracking_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/schubwerk-tracking-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    v1.0.14
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Schubwerk_Tracking_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Schubwerk_Tracking_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/schubwerk-tracking-public.js', array('jquery'), $this->version, false);

    }

    public function head_scripts(){
        echo $this->tracker->head_scripts();
    }

    public function register_routes() {
        $this->tracker->register_routes();
    }

    public function resolve_x_frame($headers){
//Elementor/Iframes issue when uncommented
//        $headers['Content-Security-Policy'] = "frame-ancestors https://tracker.schubwerk.de";
        return $headers;
    }

}
