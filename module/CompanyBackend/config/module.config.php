<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

use CompanyBackend\Controller\DisplayController;
use CompanyBackend\Controller\DisplayControllerFactory;
use CompanyBackend\Controller\ModifyController;
use CompanyBackend\Controller\ModifyControllerFactory;
use CompanyBackend\Form\CompanyForm;
use CompanyBackend\Form\CompanyFormFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'company_admin' => [
        'logo_file_path'    => PROJECT_ROOT . '/public',
        'logo_file_pattern' => '/logos/%s.png',
    ],

    'router' => [
        'routes' => [
            'company-backend' => [
                'type'          => Literal::class,
                'options'       => [
                    'route'    => '/company-backend',
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

    'controllers' => [
        'factories' => [
            DisplayController::class => DisplayControllerFactory::class,
            ModifyController::class  => ModifyControllerFactory::class,
        ],
    ],

    'view_manager' => [
        'template_map'        =>
            include COMPANY_BACKEND_MODULE_ROOT . '/config/template_map.config.php',
        'template_path_stack' => [
            COMPANY_BACKEND_MODULE_ROOT . '/view'
        ],
    ],

    'form_elements' => [
        'factories' => [
            CompanyForm::class => CompanyFormFactory::class,
        ],
    ],

    'navigation' => [
        'default' => [
            'company-backend' => [
                'type'          => 'mvc',
                'order'         => '950',
                'label'         => 'Unternehmen administrieren',
                'route'         => 'company-backend',
                'controller'    => DisplayController::class,
                'action'        => 'index',
                'useRouteMatch' => true,
                'pages'         => [
                    'edit' => [
                        'type'    => 'mvc',
                        'route'   => 'company-backend/modify',
                        'visible' => false,
                    ],
                    'show' => [
                        'type'    => 'mvc',
                        'route'   => 'company-backend/show',
                        'visible' => false,
                    ],
                    'page' => [
                        'type'    => 'mvc',
                        'route'   => 'company-backend/page',
                        'visible' => false,
                    ],
                ],
            ],
        ],
    ],
];
