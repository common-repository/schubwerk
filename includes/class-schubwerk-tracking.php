<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://schubwerk.de
 * @since      v1.0.14
 *
 * @package    Schubwerk_Tracking
 * @subpackage Schubwerk_Tracking/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      v1.0.14
 * @package    Schubwerk_Tracking
 * @subpackage Schubwerk_Tracking/includes
 * @author     schubwerk GmbH <support@schubwerk.de>
 */
class Schubwerk_Tracking
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    v1.0.14
     * @access   protected
     * @var      Schubwerk_Tracking_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    v1.0.14
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    v1.0.14
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;


    /**
     * The tracker that's responsible for generating schubwerk tracking functionality.
     *
     * @since    v1.0.14
     * @access   protected
     * @var      Schubwerk_Tracker $tracker Initialize schubwerk tracking
     */
    protected $tracker;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    v1.0.14
     */
    public function __construct()
    {
        if (defined('SCHUBWERK_TRACKING_VERSION')) {
            $this->version = SCHUBWERK_TRACKING_VERSION;
        } else {
            $this->version = 'v1.0.14';
        }
        $this->plugin_name = 'schubwerk-tracking';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Schubwerk_Tracking_Loader. Orchestrates the hooks of the plugin.
     * - Schubwerk_Tracking_i18n. Defines internationalization functionality.
     * - Schubwerk_Tracking_Admin. Defines all hooks for the admin area.
     * - Schubwerk_Tracking_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    v1.0.14
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-schubwerk-tracking-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-schubwerk-tracking-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-schubwerk-tracking-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-schubwerk-tracking-public.php';

        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-schubwerk-tracker.php';

        $this->loader = new Schubwerk_Tracking_Loader();
        $this->tracker = new Schubwerk_Tracker($this->get_plugin_name(), $this->get_version());

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Schubwerk_Tracking_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    v1.0.14
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Schubwerk_Tracking_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    v1.0.14
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new Schubwerk_Tracking_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_menu', $plugin_admin, 'add_admin_menu');
        $this->loader->add_action('admin_init', $plugin_admin, 'settings_init');
        $this->loader->add_filter('plugin_action_links_schubwerk-tracking/schubwerk-tracking.php', $plugin_admin, 'add_settings_link');
        $this->loader->add_action('pre_update_option_schubwerk-tracking_settings', $plugin_admin, 'pre_update_api_key');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    v1.0.14
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new Schubwerk_Tracking_Public($this->get_plugin_name(), $this->get_version(), $this->tracker);

        $this->loader->add_action('wp_head', $plugin_public, 'head_scripts');
        $this->loader->add_filter('wp_headers', $plugin_public, 'resolve_x_frame');

        $this->loader->add_action('rest_api_init', $plugin_public, 'register_routes');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    v1.0.14
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     * @since     v1.0.14
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    Schubwerk_Tracking_Loader    Orchestrates the hooks of the plugin.
     * @since     v1.0.14
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     v1.0.14
     */
    public function get_version()
    {
        return $this->version;
    }

}
