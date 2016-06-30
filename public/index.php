<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

use Zend\Mvc\Application;
use Zend\Stdlib\ArrayUtils;

// define application root for better file path definitions
define('PROJECT_ROOT', realpath(__DIR__ . '/..'));

// define application environment, needs to be set within virtual host
// but could be chosen by any other identifier
define(
    'APPLICATION_ENV', (getenv('APPLICATION_ENV')
    ? getenv('APPLICATION_ENV')
    : 'production')
);

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    $path = realpath(
        __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
    );

    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }

    unset($path);
}

// setup autoloading from composer
require_once PROJECT_ROOT . '/vendor/autoload.php';

// change working dir
chdir(dirname(__DIR__));

// read application configuration
$appConfig = require PROJECT_ROOT . '/config/application.config.php';

// add additional configuration for current environment
$configFile = PROJECT_ROOT . '/config/' . APPLICATION_ENV . '.config.php';
if (file_exists($configFile)) {
    $appConfig = ArrayUtils::merge($appConfig, require $configFile);
}

// run the application
Application::init($appConfig)->run();
