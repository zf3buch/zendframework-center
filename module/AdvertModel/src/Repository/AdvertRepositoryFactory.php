<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertModel\Repository;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AdvertRepositoryFactory
 *
 * @package AdvertModel\Repository
 */
class AdvertRepositoryFactory implements FactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ) {
        $advertData  = include PROJECT_ROOT . '/data/advert-data.php';
        $companyData = include PROJECT_ROOT . '/data/company-data.php';

        return new AdvertRepository($advertData, $companyData);
    }
}
