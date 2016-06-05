<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

use CompanyModel\Hydrator\CompanyHydrator;
use CompanyModel\Repository\CompanyRepositoryFactory;
use CompanyModel\Repository\CompanyRepositoryInterface;
use CompanyModel\Storage\Db\CompanyDbStorage;
use CompanyModel\Storage\Db\CompanyDbStorageFactory;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'service_manager' => [
        'factories' => [
            CompanyDbStorage::class => CompanyDbStorageFactory::class,

            CompanyRepositoryInterface::class =>
                CompanyRepositoryFactory::class
        ],
    ],

    'hydrators' => [
        'factories' => [
            CompanyHydrator::class => InvokableFactory::class,
        ],
    ],
];
