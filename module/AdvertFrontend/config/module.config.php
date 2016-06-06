<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

use AdvertFrontend\Controller\DisplayController;
use AdvertFrontend\Controller\DisplayControllerFactory;
use AdvertFrontend\Controller\ModifyController;
use AdvertFrontend\Controller\ModifyControllerFactory;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'advert-job'     => [
                'type'          => Segment::class,
                'options'       => [
                    'route'       => '/:lang/job',
                    'defaults'    => [
                        'controller' => DisplayController::class,
                        'action'     => 'index',
                        'type'       => 'job',
                        'lang'       => 'de',
                    ],
                    'constraints' => [
                        'lang' => '(de|en)',
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
                                'action' => '(add|edit|delete)',
                                'id'     => '[1-9][0-9]*',
                            ],
                        ],
                    ],
                    'detail' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'       => '/detail[/:id]',
                            'defaults'    => [
                                'action' => 'detail',
                            ],
                            'constraints' => [
                                'id' => '[1-9][0-9]*',
                            ],
                        ],
                    ],
                    'page'   => [
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
            'advert-project' => [
                'type'          => Segment::class,
                'options'       => [
                    'route'       => '/:lang/project',
                    'defaults'    => [
                        'controller' => DisplayController::class,
                        'action'     => 'index',
                        'type'       => 'project',
                        'lang'       => 'de',
                    ],
                    'constraints' => [
                        'lang' => '(de|en)',
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
                                'action' => '(add|edit|delete)',
                                'id'     => '[1-9][0-9]*',
                            ],
                        ],
                    ],
                    'detail' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'       => '/detail[/:id]',
                            'defaults'    => [
                                'action' => 'detail',
                            ],
                            'constraints' => [
                                'id' => '[1-9][0-9]*',
                            ],
                        ],
                    ],
                    'page'   => [
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
            include ADVERT_FRONTEND_MODULE_ROOT . '/template_map.php',
        'template_path_stack' => [
            ADVERT_FRONTEND_MODULE_ROOT . '/view'
        ],
    ],

    'navigation' => [
        'default' => [
            'job'     => [
                'type'          => 'mvc',
                'order'         => '200',
                'label'         => 'Jobs',
                'route'         => 'advert-job',
                'controller'    => DisplayController::class,
                'action'        => 'index',
                'useRouteMatch' => true,
                'pages'         => [
                    'edit' => [
                        'type'    => 'mvc',
                        'route'   => 'advert-job/modify',
                        'visible' => false,
                    ],
                    'show' => [
                        'type'    => 'mvc',
                        'route'   => 'advert-job/detail',
                        'visible' => false,
                    ],
                    'page' => [
                        'type'    => 'mvc',
                        'route'   => 'advert-job/page',
                        'visible' => false,
                    ],
                ],
            ],
            'project' => [
                'type'          => 'mvc',
                'order'         => '300',
                'label'         => 'Projekte',
                'route'         => 'advert-project',
                'controller'    => DisplayController::class,
                'action'        => 'index',
                'useRouteMatch' => true,
                'pages'         => [
                    'edit' => [
                        'type'    => 'mvc',
                        'route'   => 'advert-project/modify',
                        'visible' => false,
                    ],
                    'show' => [
                        'type'    => 'mvc',
                        'route'   => 'advert-project/detail',
                        'visible' => false,
                    ],
                    'page' => [
                        'type'    => 'mvc',
                        'route'   => 'advert-project/page',
                        'visible' => false,
                    ],
                ],
            ],
        ],
    ],
];