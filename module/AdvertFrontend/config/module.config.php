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
use AdvertFrontend\Permissions\Resource\DisplayResource;
use AdvertFrontend\Permissions\Resource\ModifyResource;
use UserModel\Permissions\Role\AdminRole;
use UserModel\Permissions\Role\CompanyRole;
use UserModel\Permissions\Role\GuestRole;
use Zend\Navigation\Page\Mvc;
use Zend\Permissions\Acl\Acl;
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
            include ADVERT_FRONTEND_MODULE_ROOT . '/config/template_map.config.php',
        'template_path_stack' => [
            ADVERT_FRONTEND_MODULE_ROOT . '/view'
        ],
    ],

    'navigation' => [
        'default' => [
            'job'     => [
                'type'          => Mvc::class,
                'order'         => '200',
                'label'         => 'advert_frontend_navigation_jobs',
                'route'         => 'advert-job',
                'controller'    => DisplayController::class,
                'action'        => 'index',
                'useRouteMatch' => true,
                'pages'         => [
                    'edit' => [
                        'type'    => Mvc::class,
                        'route'   => 'advert-job/modify',
                        'visible' => false,
                    ],
                    'show' => [
                        'type'    => Mvc::class,
                        'route'   => 'advert-job/detail',
                        'visible' => false,
                    ],
                    'page' => [
                        'type'    => Mvc::class,
                        'route'   => 'advert-job/page',
                        'visible' => false,
                    ],
                ],
            ],
            'project' => [
                'type'          => Mvc::class,
                'order'         => '300',
                'label'         => 'advert_frontend_navigation_projects',
                'route'         => 'advert-project',
                'controller'    => DisplayController::class,
                'action'        => 'index',
                'useRouteMatch' => true,
                'pages'         => [
                    'edit' => [
                        'type'    => Mvc::class,
                        'route'   => 'advert-project/modify',
                        'visible' => false,
                    ],
                    'show' => [
                        'type'    => Mvc::class,
                        'route'   => 'advert-project/detail',
                        'visible' => false,
                    ],
                    'page' => [
                        'type'    => Mvc::class,
                        'route'   => 'advert-project/page',
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
                'base_dir' => ADVERT_FRONTEND_MODULE_ROOT . '/language',
                'pattern'  => '%s.php',
            ],
        ],
    ],

    'acl' => [
        GuestRole::NAME   => [
            DisplayResource::NAME => [
                Acl::TYPE_ALLOW => [
                    DisplayResource::PRIVILEGE_INDEX,
                    DisplayResource::PRIVILEGE_DETAIL,
                ],
            ],
        ],
        CompanyRole::NAME => [
            DisplayResource::NAME => [
                Acl::TYPE_ALLOW => [
                    DisplayResource::PRIVILEGE_INDEX,
                    DisplayResource::PRIVILEGE_DETAIL,
                ],
            ],
            ModifyResource::NAME => [
                Acl::TYPE_ALLOW => [
                    ModifyResource::PRIVILEGE_ADD,
                    ModifyResource::PRIVILEGE_EDIT,
                    ModifyResource::PRIVILEGE_DELETE,
                ],
            ],
        ],
        AdminRole::NAME   => [
            DisplayResource::NAME => [
                Acl::TYPE_ALLOW => null,
            ],
        ],
    ],
];