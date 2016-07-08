<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

use UserFrontend\Controller\EditController;
use UserFrontend\Controller\EditControllerFactory;
use UserFrontend\Controller\IndexController;
use UserFrontend\Controller\LoginController;
use UserFrontend\Controller\LoginControllerFactory;
use UserFrontend\Controller\RegisterController;
use UserFrontend\Controller\RegisterControllerFactory;
use UserFrontend\Form\UserEditFormInterface;
use UserFrontend\Form\UserFormAbstractFactory;
use UserFrontend\Form\UserLoginFormInterface;
use UserFrontend\Form\UserLogoutFormInterface;
use UserFrontend\Form\UserRegisterFormInterface;
use UserFrontend\View\Helper\ShowEditForm;
use UserFrontend\View\Helper\ShowFormAbstractFactory;
use UserFrontend\View\Helper\ShowLoginForm;
use UserFrontend\View\Helper\ShowLogoutForm;
use UserFrontend\View\Helper\ShowRegisterForm;
use Zend\Navigation\Page\Mvc;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'user-frontend' => [
                'type'          => Segment::class,
                'options'       => [
                    'route'       => '/:lang/user',
                    'defaults'    => [
                        'controller' => IndexController::class,
                        'action'     => 'index',
                        'lang'       => 'de',
                    ],
                    'constraints' => [
                        'lang' => '(de|en)',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'edit'     => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/edit',
                            'defaults' => [
                                'controller' => EditController::class,
                            ],
                        ],
                    ],
                    'register' => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/register',
                            'defaults' => [
                                'controller' => RegisterController::class,
                            ],
                        ],
                    ],
                    'login'    => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/login',
                            'defaults' => [
                                'controller' => LoginController::class,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            IndexController::class    => InvokableFactory::class,
            EditController::class     => EditControllerFactory::class,
            RegisterController::class => RegisterControllerFactory::class,
            LoginController::class    => LoginControllerFactory::class,
        ],
    ],

    'view_manager' => [
        'template_map'        =>
            include USER_FRONTEND_MODULE_ROOT
                . '/config/template_map.config.php',
        'template_path_stack' => [
            USER_FRONTEND_MODULE_ROOT . '/view'
        ],
    ],

    'form_elements' => [
        'factories' => [
            UserEditFormInterface::class     =>
                UserFormAbstractFactory::class,
            UserLoginFormInterface::class    =>
                UserFormAbstractFactory::class,
            UserLogoutFormInterface::class   =>
                InvokableFactory::class,
            UserRegisterFormInterface::class =>
                UserFormAbstractFactory::class,
        ],
        'shared'    => [
            UserEditFormInterface::class     => true,
            UserLoginFormInterface::class    => true,
            UserLogoutFormInterface::class   => true,
            UserRegisterFormInterface::class => true,
        ],
    ],

    'view_helpers' => [
        'factories' => [
            ShowEditForm::class     => ShowFormAbstractFactory::class,
            ShowLoginForm::class    => ShowFormAbstractFactory::class,
            ShowLogoutForm::class   => ShowFormAbstractFactory::class,
            ShowRegisterForm::class => ShowFormAbstractFactory::class,
        ],
        'aliases'   => [
            'userShowEditForm'     => ShowEditForm::class,
            'userShowLoginForm'    => ShowLoginForm::class,
            'userShowLogoutForm'   => ShowLogoutForm::class,
            'userShowRegisterForm' => ShowRegisterForm::class,
        ]
    ],

    'navigation' => [
        'default' => [
            'user-frontend' => [
                'type'          => Mvc::class,
                'order'         => '800',
                'label'         => 'user_frontend_navigation_index',
                'route'         => 'user-frontend',
                'controller'    => IndexController::class,
                'action'        => 'index',
                'useRouteMatch' => true,
                'pages'         => [
                    'edit'     => [
                        'type'    => Mvc::class,
                        'route'   => 'user-frontend/edit',
                        'visible' => false,
                    ],
                    'register' => [
                        'type'    => Mvc::class,
                        'route'   => 'user-frontend/register',
                        'visible' => false,
                    ],
                    'login'    => [
                        'type'    => Mvc::class,
                        'route'   => 'user-frontend/login',
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
                'base_dir' => USER_FRONTEND_MODULE_ROOT . '/language',
                'pattern'  => '%s.php',
            ],
        ],
    ],
];
