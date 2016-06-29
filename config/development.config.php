<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

return [
    'modules'                 => [
        'Zend\Paginator',
        'Zend\Navigation',
        'AdvertFrontend',
        'AdvertBackend',
        'AdvertModel',
        'ZendDeveloperTools',
        'SanSessionToolbar',
        'Zend\Session',
        'Zend\Router',
        'Zend\Validator',
        'Application',
    ],
    'module_listener_options' => [
        'config_glob_paths'        => [
            PROJECT_ROOT
            . '/config/autoload/{,*.}{global,development,local}.php',
        ],
        'module_paths'             => [
            PROJECT_ROOT . '/module',
            PROJECT_ROOT . '/vendor',
        ],
        'cache_dir'                => PROJECT_ROOT . '/data/cache',
        'config_cache_enabled'     => false,
        'config_cache_key'         => 'module_config_cache',
        'module_map_cache_enabled' => false,
        'module_map_cache_key'     => 'module_map_cache',
    ],
];
