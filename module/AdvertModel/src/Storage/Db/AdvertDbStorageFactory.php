<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertModel\Storage\Db;

use AdvertModel\Entity\AdvertEntity;
use AdvertModel\Hydrator\AdvertHydrator;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AdvertDbStorageFactory
 *
 * @package AdvertModel\Storage\Db
 */
class AdvertDbStorageFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return AdvertDbStorage
     */
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ) {
        /** @var AdapterInterface $dbAdapter */
        $dbAdapter = $container->get(Adapter::class);

        $resultSetPrototype = new HydratingResultSet(
            new AdvertHydrator(),
            new AdvertEntity()
        );

        $tableGateway = new TableGateway(
            'advert', $dbAdapter, null, $resultSetPrototype
        );

        $storage = new AdvertDbStorage($tableGateway);

        return $storage;
    }
}
