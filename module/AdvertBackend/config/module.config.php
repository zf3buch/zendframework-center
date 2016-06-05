<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

use AdvertBackend\Controller\DisplayController;
use AdvertBackend\Controller\DisplayControllerFactory;
use AdvertBackend\Controller\ModifyController;
use AdvertBackend\Controller\ModifyControllerFactory;
use AdvertBackend\Form\AdvertForm;
use AdvertBackend\Form\AdvertFormFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'advert-backend' => [
                'type'          => Literal::class,
                'options'       => [
                    'route'    => '/advert-admin',
                    'defaults' => [
                        'controller' => DisplayController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'modify' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'       => '/:action[/:id]',
                            'defaults'    => [
                                'controller' => ModifyController::class,
                            ],
                            'constraints' => [
                                'action' => '(add|edit|delete|approve|block)',
                                'id'     => '[1-9][0-9]*',
                            ],
                        ],
                    ],
                    'show' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'       => '/show[/:id]',
                            'defaults'    => [
                                'action' => 'show',
                            ],
                            'constraints' => [
                                'id'     => '[1-9][0-9]*',
                            ],
                        ],
                    ],
                    'page' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'       => '/:page',
                            'constraints' => [
                                'page' => '[1-9][0-9]*',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            DisplayController::class => DisplayControllerFactory::class,
            ModifyController::class  => ModifyControllerFactory::class,
        ],
    ],

    'view_manager' => [
        'template_map'        =>
            include ADVERT_BACKEND_MODULE_ROOT . '/template_map.php',
        'template_path_stack' => [
            ADVERT_BACKEND_MODULE_ROOT . '/view'
        ],
    ],

    'form_elements' => [
        'factories' => [
            AdvertForm::class => AdvertFormFactory::class,
        ],
    ],

    'navigation' => [
        'default' => [
            'advert-admin' => [
                'type'       => 'mvc',
                'order'      => '900',
                'label'      => 'Annoncen administrieren',
                'route'      => 'advert-backend',
                'controller' => DisplayController::class,
                'action'     => 'index',
                'useRouteMatch' => true,
                'pages'      => [
                    'edit' => [
                        'type'       => 'mvc',
                        'route'      => 'advert-backend/modify',
                        'visible'    => false,
                    ],
                    'show' => [
                        'type'       => 'mvc',
                        'route'      => 'advert-backend/show',
                        'visible'    => false,
                    ],
                    'page' => [
                        'type'       => 'mvc',
                        'route'      => 'advert-backend/page',
                        'visible'    => false,
                    ],
                ],
            ],
        ],
    ],
];
