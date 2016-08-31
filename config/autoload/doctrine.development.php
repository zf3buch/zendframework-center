<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params'      => [
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'zf-center',
                    'password' => 'geheim',
                    'dbname'   => 'zf-center',
                ]
            ]
        ]
    ],
];
