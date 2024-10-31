<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://schubwerk.de
 * @since     v1.0.14
 *
 * @package    Schubwerk_Tracking
 * @subpackage Schubwerk_Tracking/admin
 */

use Schubwerk\Core\Downloader;
use Schubwerk\Core\DownloadException;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Schubwerk_Tracking
 * @subpackage Schubwerk_Tracking/admin
 * @author     schubwerk GmbH <support@schubwerk.de>
 */
class Schubwerk_Tracking_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since   v1.0.14
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since   v1.0.14
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since   v1.0.14
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register plugin settings page.
     *
     * @since  v1.0.14
     */
    public function add_admin_menu()
    {
        add_options_page('schubwerk Tracking', 'schubwerk Tracking', 'manage_options', 'schubwerk-tracking', array($this, 'options_page'));
    }

    /**
     * Admin page with plugin settings.
     *
     * @since    v1.0.14
     */
    public function options_page()
    {
        // check user capabilities.
        if (!current_user_can('manage_options')) {
            return;
        }

        ?>
        <form action='options.php' method='post'>

            <?php
            settings_fields($this->plugin_name);
            do_settings_sections($this->plugin_name);
            submit_button();
            ?>

        </form>
        <?php
    }

    /**
     * Register admin page settins for this plugin.
     *
     * @since       v1.0.14
     */
    public function settings_init()
    {
        register_setting(
            $this->plugin_name,
            'schubwerk-tracking_settings',
            array(
                'sanitize_callback' => array($this, 'validate_options'),
                'default' => array(
                    'schubwerk-tracking_enabled_events' => 'true',
                ),
            )
        );

        add_settings_section(
            'schubwerk-tracking_pluginPage_section',
            __('schubwerk Tracking', 'schubwerk-tracking'),
            array($this, 'settings_section_callback'),
            $this->plugin_name
        );

        add_settings_field(
            'schubwerk-tracking_api_key',
            __('Account Key', 'schubwerk-tracking'),
            array($this, 'render_api_key_block'),
            $this->plugin_name,
            'schubwerk-tracking_pluginPage_section'
        );

        add_settings_field(
            'schubwerk-tracking_events',
            __('Event tracking', 'schubwerk-tracking'),
            array($this, 'render_event_tracking_block'),
            $this->plugin_name,
            'schubwerk-tracking_pluginPage_section'
        );

        add_settings_field(
            'schubwerk-tracking_base_url',
            __('Overwrite Tracker Base URL (optional)', 'schubwerk-tracking'),
            array($this, 'render_base_url_block'),
            $this->plugin_name,
            'schubwerk-tracking_pluginPage_section'
        );

    }

    /**
     * Validate setting values before processing them.
     *
     * @param array $options Options submitted by settings form.
     * @return  array Validated options
     * @since   v1.0.14
     */
    public function validate_options($options)
    {
        $validated = array();
        $validated['schubwerk-tracking_enabled_events'] = (isset($options['schubwerk-tracking_enabled_events']) && $options['schubwerk-tracking_enabled_events'] === 'true') ? 'true' : 'false';
        $validated['schubwerk-tracking_api_key'] = $options['schubwerk-tracking_api_key'] ?? '';
        $validated['schubwerk-tracking_base_url'] = rtrim($options['schubwerk-tracking_base_url'], '/') ?? '';

        return $validated;
    }

    /**
     * Admin page settings description callback.
     *
     * @since   v1.0.14
     */
    public function settings_section_callback()
    {
        esc_html_e('This plugin enables server side for your entire WordPress site.', 'schubwerk-tracking');
        ?>
        <p><?php esc_html_e('How to use this plugin', 'schubwerk-tracking'); ?>:</p>
        <ol>
            <li><?php esc_html_e('Enter your schubwerk-tracking Account Key', 'schubwerk-tracking'); ?></li>
            <li><?php esc_html_e('Choose either to enable or disable event tracking', 'schubwerk-tracking'); ?></li>
            <li><?php esc_html_e('Save changes', 'schubwerk-tracking'); ?></li>
        </ol>
        <?php
    }

    /**
     * Render secure code settings form part for admin page.
     *
     * @since       v1.0.14
     */
    public function render_api_key_block()
    {
        $options = get_option('schubwerk-tracking_settings');
        ?>
        <input type='text' name='schubwerk-tracking_settings[schubwerk-tracking_api_key]' size='35'
               value='<?php echo esc_attr($options['schubwerk-tracking_api_key'] ?? ''); ?>'>
        <p class="description">
            <?php esc_html_e('Activate plugin by entering your individual Account Key.', 'schubwerk-tracking'); ?>
            <a href="https://tracker.schubwerk.de?source=wp"
               target="_blank"><?php esc_html_e('Signup for free.', 'schubwerk-tracking'); ?></a>
        </p>
        <?php
    }

    /**
     * Render block-cookies settings form part for admin page.
     *
     * @since       v1.0.14
     */
    public function render_event_tracking_block()
    {
        $options = get_option('schubwerk-tracking_settings');
        ?>
        <input type='checkbox'
               name='schubwerk-tracking_settings[schubwerk-tracking_enabled_events]' <?php checked('true', $options['schubwerk-tracking_enabled_events']); ?>
               value='true'>
        <p class="description">
            <?php esc_html_e('Set to true, if you want to track events like form submissions, downloads and klick', 'schubwerk-tracking'); ?>
        </p>
        <?php
    }

    public function render_base_url_block()
    {
        $options = get_option('schubwerk-tracking_settings');
        ?>
        <input type='text' name='schubwerk-tracking_settings[schubwerk-tracking_base_url]' size='35'
               value='<?php echo esc_attr($options['schubwerk-tracking_base_url'] ?? ''); ?>'>
        <p class="description">
            <?php esc_html_e('Set an alternative URL for the Tracker. Normally leave empty.', 'schubwerk-tracking'); ?>
        </p>
        <?php
    }

    /**
     * Render settings link next to disable on plugin page.
     *
     * @param array $links Action links to be filtered.
     * @return  array $links
     * @since       1.5.0
     */
    public function add_settings_link($links)
    {
        // Build and escape the URL.
        $url = esc_url(
            add_query_arg(
                'page',
                'schubwerk-tracking',
                get_admin_url() . 'admin.php'
            )
        );
        // Create the link.
        $settings_link = "<a href='$url'>" . __('Settings', 'schubwerk-tracking') . '</a>';
        // Adds the link to the beginning of the array.
        array_unshift(
            $links,
            $settings_link
        );
        return $links;
    }

    public function pre_update_api_key($value)
    {
        require_once(__DIR__ . '/../vendor/autoload.php');

        require_once(__DIR__ . '/../public/class-schubwerk-tracker.php');

        if (!empty($value['schubwerk-tracking_base_url'])) {
            $baseUrl = rtrim($value['schubwerk-tracking_base_url'], '/');
        } else {
            $baseUrl = \Schubwerk_Tracker::DEFAULT_BASE_URL;
        }

        try {
            (new Downloader(
                $baseUrl,
                $value['schubwerk-tracking_api_key'],
                get_temp_dir(),
                plugin_dir_path(dirname(__FILE__)) . 'public/',
            ))->download(true);
            add_settings_error( 'general', 'settings_updated', 'tracking.js and city db downloaded', 'success' );
        } catch (DownloadException $e) {
            add_settings_error( 'general', 'settings_updated', $e->getMessage(), 'error' );
        }
        return $value;
    }

}
