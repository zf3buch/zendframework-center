<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace CompanyModel\Storage\Db;

use CompanyModel\Entity\CompanyEntity;
use CompanyModel\Hydrator\CompanyHydrator;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class CompanyDbStorageFactory
 *
 * @package CompanyModel\Storage\Db
 */
class CompanyDbStorageFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null|null    $options
     *
     * @return mixed
     */
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ) {
        /** @var AdapterInterface $dbAdapter */
        $dbAdapter = $container->get(Adapter::class);

        $resultSetPrototype = new HydratingResultSet(
            new CompanyHydrator(),
            new CompanyEntity()
        );

        $tableGateway = new TableGateway(
            'company', $dbAdapter, null, $resultSetPrototype
        );

        $storage = new CompanyDbStorage($tableGateway);

        return $storage;
    }
}
