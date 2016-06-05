<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace CompanyModel\Repository;

use CompanyModel\Storage\Db\CompanyDbStorage;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class CompanyRepositoryFactory
 *
 * @package CompanyModel\Repository
 */
class CompanyRepositoryFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $container
     *
     * @return CompanyRepository
     */
    public function createService(ServiceLocatorInterface $container)
    {
        $companyDbStorage = $container->get(CompanyDbStorage::class);
        
        return new CompanyRepository($companyDbStorage);
    }
}
