<?php

namespace KinetiseApi;

use KinetiseApi\HTTP\KinetiseResponse;
use KinetiseApi\View\Presenter;

class Bootstrap
{
    private $kernel;
    private $matcher;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
        $this->matcher = new UrlMatcher();
    }

    /**
     * Plugin initialization
     */
    public function init()
    {
        \add_action('template_redirect', array($this, 'handleApi'));

        $matcher = $this->matcher;
        \add_filter( 'wp_die_handler', function () use ($matcher) {

            if ($matcher->getRequest()->query->has(UrlMatcher::API_KEY)) {
                return function ( $message, $title, $args ) {
                    Presenter::send(new KinetiseResponse($message, KinetiseResponse::HTTP_BAD_REQUEST));
                };
            }

            return '_default_wp_die_handler';
        } );

        $url = \plugins_url('css/kinetise-api.css', KINETISE_ROOT . DS . 'kinetise.php');

        \wp_enqueue_style(
            'kinetise-plugin-stylesheet',
            \plugins_url('css/kinetise-api.css', KINETISE_ROOT . DS . 'kinetise.php')
        );

        \add_action('admin_menu', array($this, 'addAdminMenu'));
        \add_action('update_option_json_api_base', array($this, 'flushRewriteRules'));
    }

    public function handleApi()
    {
        $this->matcher->executeRoute();
    }

    /**
     * Plugin activation
     */
    public function activate()
    {
        global $wp_rewrite;

        if (version_compare(PHP_VERSION, KINETISE_MINIMUM_PHP, '<')) {
            \deactivate_plugins(KINETISE_ROOT . DS . 'kinetise.php');
            \wp_die('<p>The <strong>Kinetise API</strong> plugin requires minimun PHP version '.KINETISE_MINIMUM_PHP.' or greater.</p>','Plugin Activation Error',  array( 'response'=>200, 'back_link'=>TRUE ) );
        } else {
            \add_filter('rewrite_rules_array', function ($wp_rules) {
                $base = \get_option('json_api_base', 'api');
                if (empty($base)) {
                    return $wp_rules;
                }
                $kinetise_api_rules = array(
                    "$base/(.+)\$" => 'index.php?' . UrlMatcher::API_KEY . '=$matches[1]'
                );
                return array_merge($kinetise_api_rules, $wp_rules);
            });
            $message = \get_site_url(). "?kinetiseapi \r\n" . "Plugin version: " . KINETISE_PLUGIN_VERSION;
            $headers = 'From: WP-PLUGIN <wp-plugin@kinetise.com>' . "\r\n";
            \wp_mail('wordpress@kinetise.com', 'wordpress plugin activated', $message, $headers);

            $wp_rewrite->flush_rules();
        }
    }

    /**
     * Plugin deactivation
     */
    public function deactivate()
    {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }

    public function flushRewriteRules()
    {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }

    public function addAdminMenu()
    {
        \add_menu_page(
            'Kinetise API',
            'Kinetise API',
            'manage_options',
            'kinetise-api',
            '',
            \plugins_url('images/kinetise-logo.png', KINETISE_ROOT . DS . 'kinetise.php')
        );

        \add_submenu_page(
            'kinetise-api',
            'Tutorial',
            'Tutorial',
            'manage_options',
            'kinetise-api-tutorial',
            function () {
                ob_start();
                include KINETISE_ROOT . DS . 'pluginView' . DS . 'tutorial.php';
                $rendered = ob_get_clean();
                echo $rendered;
            }
        );

        \add_submenu_page(
            'kinetise-api',
            'Reference',
            'Reference',
            'manage_options',
            'kinetise-api-reference',
            function () {
                ob_start();
                include KINETISE_ROOT . DS . 'pluginView' . DS . 'reference.php';
                $rendered = ob_get_clean();
                echo $rendered;
            }
        );

        \remove_submenu_page('kinetise-api', 'kinetise-api');
    }
}
