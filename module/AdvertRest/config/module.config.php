<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

use AdvertRest\Controller\RestController;
use AdvertRest\Controller\RestControllerFactory;
use AdvertRest\Permissions\Resource\RestResource;
use UserModel\Permissions\Role\AdminRole;
use UserModel\Permissions\Role\CompanyRole;
use UserModel\Permissions\Role\GuestRole;
use Zend\Permissions\Acl\Acl;

return [
    'router' => [
        'routes' => [
            'advert-job-rest'     => [
                'type'    => 'segment',
                'options' => [
                    'route'       => '/:lang/rest/job[/:id]',
                    'defaults'    => [
                        'controller' => RestController::class,
                        'type'       => 'job',
                        'lang'       => 'de',
                    ],
                    'constraints' => [
                        'lang' => '(de|en)',
                        'id'   => '[0-9]*',
                    ],
                ],
            ],
            'advert-project-rest' => [
                'type'    => 'segment',
                'options' => [
                    'route'       => '/:lang/rest/project[/:id]',
                    'defaults'    => [
                        'controller' => RestController::class,
                        'type'       => 'project',
                        'lang'       => 'de',
                    ],
                    'constraints' => [
                        'lang' => '(de|en)',
                        'id'   => '[0-9]*',
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            RestController::class => RestControllerFactory::class,
        ],
    ],

    'translator' => [
        'translation_file_patterns' => [
            [
                'type'     => 'phparray',
                'base_dir' => ADVERT_REST_MODULE_ROOT . '/language',
                'pattern'  => '%s.php',
            ],
        ],
    ],

    'acl' => [
        GuestRole::NAME   => [
            RestResource::NAME => [
                Acl::TYPE_ALLOW => null,
            ],
        ],
        CompanyRole::NAME => [
            RestResource::NAME => [
                Acl::TYPE_ALLOW => null,
            ],
        ],
        AdminRole::NAME   => [
            RestResource::NAME => [
                Acl::TYPE_ALLOW => null,
            ],
        ],
    ],
];
