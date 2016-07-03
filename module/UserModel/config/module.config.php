<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

use UserModel\Config\UserConfigFactory;
use UserModel\Config\UserConfigInterface;
use UserModel\Hydrator\UserHydrator;
use UserModel\InputFilter\UserInputFilter;
use UserModel\InputFilter\UserInputFilterFactory;
use UserModel\Repository\UserRepositoryFactory;
use UserModel\Repository\UserRepositoryInterface;
use UserModel\Storage\Db\UserDbStorage;
use UserModel\Storage\Db\UserDbStorageFactory;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'service_manager' => [
        'factories' => [
            UserConfigInterface::class => UserConfigFactory::class,

            UserDbStorage::class => UserDbStorageFactory::class,

            UserRepositoryInterface::class =>
                UserRepositoryFactory::class
        ],
    ],

    'hydrators' => [
        'factories' => [
            UserHydrator::class => InvokableFactory::class,
        ],
    ],

    'input_filters' => [
        'factories' => [
            UserInputFilter::class => UserInputFilterFactory::class,
        ],
    ],

    'translator' => [
        'translation_file_patterns' => [
            [
                'type'     => 'phparray',
                'base_dir' => USER_MODEL_MODULE_ROOT . '/language',
                'pattern'  => '%s.php',
            ],
        ],
    ],
];
