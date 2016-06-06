<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Application;

use Application\Controller\IndexController;
use Application\Controller\IndexControllerFactory;
use Application\I18n\I18nListener;
use Application\I18n\I18nListenerFactory;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/[:lang]',
                    'defaults'    => [
                        'controller' => IndexController::class,
                        'action'     => 'index',
                        'lang'       => 'de',
                    ],
                    'constraints' => [
                        'lang' => '(de|en)',
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class,
        ],
    ],

    'service_manager' => [
        'factories' => [
            I18nListener::class => I18nListenerFactory::class,
        ],
    ],

    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map'             =>
            include APPLICATION_MODULE_ROOT . '/template_map.php',
        'template_path_stack'      => [
            APPLICATION_MODULE_ROOT . '/view',
        ],
    ],

    'i18n' => [
        'defaultLang'    => 'de',
        'allowedLocales' => [
            'de' => 'de_DE',
            'en' => 'en_US',
        ],
        'defaultRoute'   => 'home',
    ],

    'navigation' => [
        'default' => [
            'application' => [
                'type'          => 'mvc',
                'order'         => '100',
                'label'         => 'application_navigation_home',
                'route'         => 'home',
                'controller'    => IndexController::class,
                'action'        => 'index',
                'useRouteMatch' => true,
            ],
        ],
    ],

    'translator' => [
        'translation_file_patterns' => [
            [
                'type'     => 'phparray',
                'base_dir' => APPLICATION_MODULE_ROOT . '/language',
                'pattern'  => '%s.php',
            ],
        ],
    ],
];
