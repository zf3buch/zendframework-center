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
use AdvertBackend\Permissions\Resource\DisplayResource;
use AdvertBackend\Permissions\Resource\ModifyResource;
use UserModel\Permissions\Role\AdminRole;
use UserModel\Permissions\Role\GuestRole;
use Zend\Navigation\Page\Mvc;
use Zend\Permissions\Acl\Acl;
use Zend\Router\Http\Segment;

return [
    'router'        => [
        'routes' => [
            'advert-backend' => [
                'type'          => Segment::class,
                'options'       => [
                    'route'       => '/:lang/advert-backend',
                    'defaults'    => [
                        'controller' => DisplayController::class,
                        'action'     => 'index',
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
                                'action' => '(add|edit|delete|approve|block)',
                                'id'     => '[1-9][0-9]*',
                            ],
                        ],
                    ],
                    'show'   => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'       => '/show[/:id]',
                            'defaults'    => [
                                'action' => 'show',
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

    'controllers'   => [
        'factories' => [
            DisplayController::class => DisplayControllerFactory::class,
            ModifyController::class  => ModifyControllerFactory::class,
        ],
    ],

    'view_manager'  => [
        'template_map'        =>
            include ADVERT_BACKEND_MODULE_ROOT . '/config/template_map.config.php',
        'template_path_stack' => [
            ADVERT_BACKEND_MODULE_ROOT . '/view'
        ],
    ],

    'form_elements' => [
        'factories' => [
            AdvertForm::class => AdvertFormFactory::class,
        ],
    ],

    'navigation'    => [
        'default' => [
            'advert-backend' => [
                'type'          => Mvc::class,
                'order'         => '900',
                'label'         => 'advert_backend_navigation_admin',
                'route'         => 'advert-backend',
                'controller'    => DisplayController::class,
                'action'        => 'index',
                'useRouteMatch' => true,
                'pages'         => [
                    'edit' => [
                        'type'    => Mvc::class,
                        'route'   => 'advert-backend/modify',
                        'visible' => false,
                    ],
                    'show' => [
                        'type'    => Mvc::class,
                        'route'   => 'advert-backend/show',
                        'visible' => false,
                    ],
                    'page' => [
                        'type'    => Mvc::class,
                        'route'   => 'advert-backend/page',
                        'visible' => false,
                    ],
                ],
            ],
        ],
    ],

    'translator' => [
        'translation_file_patterns' => [
            [
                'type'     => 'phparray',
                'base_dir' => ADVERT_BACKEND_MODULE_ROOT . '/language',
                'pattern'  => '%s.php',
            ],
        ],
    ],

    'acl' => [
        AdminRole::NAME   => [
            DisplayResource::NAME => [
                Acl::TYPE_ALLOW => null,
            ],
            ModifyResource::NAME => [
                Acl::TYPE_ALLOW => null,
            ],
        ],
    ],
];
