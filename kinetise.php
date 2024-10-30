<?php

/**
 * Plugin Name: Kinetise API
 * Description: Provide API for your blog to build apps by kinetise.com using wordpress blog
 * Version: 2.0.5
 * Author: KINETISE ©
 * Author URI: https://www.kinetise.com/
 */

use KinetiseApi\Kernel;
use KinetiseApi\Exception\ExceptionHandler;

if (!defined('KINETISE_ROOT')) {
    define('KINETISE_ROOT', dirname(__FILE__));
}

if (!defined('KINETISE_PLUGIN_VERSION')) {
    define('KINETISE_PLUGIN_VERSION', '2.0.5');
}

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

if (!defined('KINETISE_MINIMUM_PHP')) {
    define('KINETISE_MINIMUM_PHP', '5.3.9');
}

$vendor = KINETISE_ROOT . DS . 'vendor';
$src = KINETISE_ROOT . DS . 'src';

require_once $vendor . DS . 'autoload.php';

set_exception_handler(function(\Exception $e) {
    ExceptionHandler::handle($e);
});

$kernel = new Kernel();

\add_action('init', function () use ($kernel) {
    $kernel->boot();
});

\register_activation_hook(KINETISE_ROOT . '/kinetise.php', function () use ($kernel) {
    $kernel->getBootstrap()->activate();
});

\register_deactivation_hook(KINETISE_ROOT . '/kinetise.php', function () use ($kernel) {
    $kernel->getBootstrap()->deactivate();
});
