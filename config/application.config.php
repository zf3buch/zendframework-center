<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

return [
    'modules' => [
        'TravelloViewHelper',
        'Zend\Mvc\Plugin\FlashMessenger',
        'Zend\I18n',
        'Zend\Form',
        'Zend\InputFilter',
        'Zend\Db',
        'Zend\Filter',
        'Zend\Hydrator',
        'Zend\Paginator',
        'Zend\Navigation',
        'Zend\Session',
        'Zend\Router',
        'Zend\Validator',
        'CompanyBackend',
        'CompanyModel',
        'AdvertFrontend',
        'AdvertBackend',
        'AdvertModel',
        'Application',
    ],
    'module_listener_options' => [
        'module_paths' => [
            PROJECT_ROOT . '/module',
            PROJECT_ROOT . '/vendor',
        ],
        'cache_dir'                => PROJECT_ROOT . '/data/cache',
        'config_cache_enabled'     => true,
        'config_cache_key'         => 'application.config.cache',
        'module_map_cache_enabled' => true,
        'module_map_cache_key'     => 'application.module.cache',
    ],
];
