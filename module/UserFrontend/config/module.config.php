<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

use UserFrontend\Authentication\Adapter\AdapterFactory;
use UserFrontend\Authentication\AuthenticationListenerFactory;
use UserFrontend\Authentication\AuthenticationListenerInterface;
use UserFrontend\Authentication\AuthenticationServiceFactory;
use UserFrontend\Authorization\AuthorizationListenerFactory;
use UserFrontend\Authorization\AuthorizationListenerInterface;
use UserFrontend\Controller\EditController;
use UserFrontend\Controller\EditControllerFactory;
use UserFrontend\Controller\ForbiddenController;
use UserFrontend\Controller\IndexController;
use UserFrontend\Controller\IndexControllerFactory;
use UserFrontend\Controller\LoginController;
use UserFrontend\Controller\LoginControllerFactory;
use UserFrontend\Controller\RegisterController;
use UserFrontend\Controller\RegisterControllerFactory;
use UserFrontend\Form\UserEditForm;
use UserFrontend\Form\UserFormAbstractFactory;
use UserFrontend\Form\UserLoginForm;
use UserFrontend\Form\UserLogoutForm;
use UserFrontend\Form\UserRegisterForm;
use UserFrontend\Permissions\Resource\EditResource;
use UserFrontend\Permissions\Resource\ForbiddenResource;
use UserFrontend\Permissions\Resource\IndexResource;
use UserFrontend\Permissions\Resource\LoginResource;
use UserFrontend\Permissions\Resource\RegisterResource;
use UserFrontend\View\Helper\ShowEditForm;
use UserFrontend\View\Helper\ShowFormAbstractFactory;
use UserFrontend\View\Helper\ShowLoginForm;
use UserFrontend\View\Helper\ShowLogoutForm;
use UserFrontend\View\Helper\ShowRegisterForm;
use UserModel\Permissions\Role\AdminRole;
use UserModel\Permissions\Role\CompanyRole;
use UserModel\Permissions\Role\GuestRole;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Navigation\Page\Mvc;
use Zend\Permissions\Acl\Acl;
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
                    'edit'      => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/edit',
                            'defaults' => [
                                'controller' => EditController::class,
                            ],
                        ],
                    ],
                    'register'  => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/register',
                            'defaults' => [
                                'controller' => RegisterController::class,
                            ],
                        ],
                    ],
                    'login'     => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/login',
                            'defaults' => [
                                'controller' => LoginController::class,
                            ],
                        ],
                    ],
                    'forbidden' => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/forbidden',
                            'defaults' => [
                                'controller' => ForbiddenController::class,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            IndexController::class     => IndexControllerFactory::class,
            EditController::class      => EditControllerFactory::class,
            RegisterController::class  => RegisterControllerFactory::class,
            LoginController::class     => LoginControllerFactory::class,
            ForbiddenController::class => InvokableFactory::class,
        ],
    ],

    'service_manager' => [
        'factories' => [
            AdapterInterface::class => AdapterFactory::class,

            AuthenticationService::class =>
                AuthenticationServiceFactory::class,

            AuthenticationListenerInterface::class =>
                AuthenticationListenerFactory::class,

            AuthorizationListenerInterface::class =>
                AuthorizationListenerFactory::class,
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
        'abstract_factories' => [
            UserFormAbstractFactory::class,
        ],
        'factories' => [
            UserLogoutForm::class   => InvokableFactory::class,
        ],
        'shared'    => [
            UserEditForm::class     => true,
            UserLoginForm::class    => true,
            UserLogoutForm::class   => true,
            UserRegisterForm::class => true,
        ],
    ],

    'view_helpers' => [
        'abstract_factories' => [
            ShowFormAbstractFactory::class,
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
                    'edit'      => [
                        'type'    => Mvc::class,
                        'route'   => 'user-frontend/edit',
                        'visible' => false,
                    ],
                    'register'  => [
                        'type'    => Mvc::class,
                        'route'   => 'user-frontend/register',
                        'visible' => false,
                    ],
                    'login'     => [
                        'type'    => Mvc::class,
                        'route'   => 'user-frontend/login',
                        'visible' => false,
                    ],
                    'forbidden' => [
                        'type'    => Mvc::class,
                        'route'   => 'user-frontend/forbidden',
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

    'acl' => [
        GuestRole::NAME   => [
            IndexResource::NAME => [
                Acl::TYPE_ALLOW => null,
            ],
            LoginResource::NAME => [
                Acl::TYPE_ALLOW => null,
            ],
            RegisterResource::NAME => [
                Acl::TYPE_ALLOW => null,
            ],
        ],
        CompanyRole::NAME   => [
            IndexResource::NAME => [
                Acl::TYPE_ALLOW => null,
            ],
            EditResource::NAME => [
                Acl::TYPE_ALLOW => null,
            ],
            ForbiddenResource::NAME => [
                Acl::TYPE_ALLOW => null,
            ],
        ],
        AdminRole::NAME   => [
            IndexResource::NAME => [
                Acl::TYPE_ALLOW => null,
            ],
            EditResource::NAME => [
                Acl::TYPE_ALLOW => null,
            ],
            ForbiddenResource::NAME => [
                Acl::TYPE_ALLOW => null,
            ],
        ],
    ],
];
