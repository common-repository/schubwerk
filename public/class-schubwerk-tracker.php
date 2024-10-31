<?php

use Schubwerk\Core\Downloader;
use Schubwerk\Core\DownloadException;
use Schubwerk\Core\Forwarder;

class Schubwerk_Tracker
{

    const API_VERSION = 'v1';

    const DEFAULT_BASE_URL = 'https://tracker.schubwerk.de';

    protected $plugin_name;
    protected $plugin_version;
    protected $options;

    public function __construct(string $plugin_name, string $plugin_version)
    {
        $this->plugin_name = $plugin_name;
        $this->plugin_version = $plugin_version;
        $this->options = get_option('schubwerk-tracking_settings');
    }

    public function head_scripts()
    {
        if (
            isset($this->options['schubwerk-tracking_api_key'], $this->options['schubwerk-tracking_enabled_events'])
            && strlen($this->options['schubwerk-tracking_api_key']) > 1
            && $this->options['schubwerk-tracking_enabled_events']
            && !current_user_can('manage_options')
        ) {
            $this->downloadAssets();

            $placeholders = [
                '{{TRACKER_URL}}' => $this->getScriptUrl(),
                '{{PROJECT_KEY}}' => 'local' /*$this->options['schubwerk-tracking_api_key']*/,
                '{{WRITE_KEY}}' => $this->options['schubwerk-tracking_api_key'],
                '{{API_END_POINT}}' => str_replace(['https://', 'http://'],'',$this->getLocalApiUrl()),
                '{{ORIGIN}}' => rtrim($this->getBaseUrl(), '/') . '/',
                '{{PROTOCOL}}' => parse_url($this->getLocalApiUrl(), PHP_URL_SCHEME),
                '{{VERSION}}' => self::API_VERSION,
                '{{RECORD_PAGE_VIEWS}}' => 'true',
            ];
            $script = file_get_contents(plugin_dir_path( __FILE__ ) . '/scaffolding/tracker.js.template');
            $script = str_replace(array_keys($placeholders), array_values($placeholders), $script);
            $dom = new DOMDocument('1.0', 'utf-8');
            $dom_tracker = $dom->createElement('script');
            $dom_tracker->setAttribute('id', 'schubwerk_tracking');
            $dom_tracker->textContent = $script;
            $dom->appendChild( $dom_tracker );
            return $dom->saveHTML();
        }

        return false;
    }

    private function getScriptUrl(): string
    {
        return plugin_dir_url(__FILE__) . 'shwk-assets/sclient.js';
    }

    private function getBaseUrl(): string
    {
        if (isset($this->options['schubwerk-tracking_base_url']) && !empty($this->options['schubwerk-tracking_base_url'])) {
            return rtrim($this->options['schubwerk-tracking_base_url'], '/');
        }
        return self::DEFAULT_BASE_URL;
    }

    private function getLocalApiUrl(): string
    {
        return rest_url('/shwkcore');
    }

    // Register our routes.
    public function register_routes() {
        register_rest_route( '/shwkcore/v1/projects/local/events/', '(?P<event>[a-z]*)', array(
            // Notice how we are registering multiple endpoints the 'schema' equates to an OPTIONS request.
            array(
                'methods'   => 'POST',
                'callback'  => array( $this, 'collect' ),
                'permission_callback' => array( $this, 'collect_permissions_check' ),
            ),
        ) );
    }

    public function collect_permissions_check( $request ) {
        return true;
    }

    public function collect(WP_REST_Request $request)
    {
        if (empty( $this->options['schubwerk-tracking_api_key'])) {
            return;
        }

        require_once(__DIR__ . '/../vendor/autoload.php');
        $event = $request->get_url_params()['event'];

        (new Forwarder(
            $this->getBaseUrl(),
            $this->options['schubwerk-tracking_api_key'],
            get_temp_dir(),
            plugin_dir_path(__FILE__),
            'WordPress/' . get_bloginfo( 'version' ) . '; SchubwerkTracking/' . SCHUBWERK_TRACKING_VERSION)
        )->forwardServerInput($_SERVER['REMOTE_ADDR'], $event);
    }

    private function downloadAssets()
    {
        require_once(__DIR__ . '/../vendor/autoload.php');

        try {
            (new Downloader(
                $this->getBaseUrl(),
                $this->options['schubwerk-tracking_api_key'],
                get_temp_dir(),
                plugin_dir_path(dirname(__FILE__)) . 'public/',
            ))->setDownloadIfTooOld(false)
                ->download();
        } catch (DownloadException $e) {
        }
    }
}
