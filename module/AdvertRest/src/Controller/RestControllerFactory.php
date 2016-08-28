<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertRest\Controller;

use AdvertModel\Hydrator\AdvertHydrator;
use AdvertModel\InputFilter\AdvertInputFilter;
use AdvertModel\Repository\AdvertRepositoryInterface;
use Interop\Container\ContainerInterface;
use Zend\Hydrator\HydratorPluginManager;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterPluginManager;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class RestControllerFactory
 *
 * @package AdvertRest\Controller
 */
class RestControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return RestController
     */
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ) {
        /** @var HydratorPluginManager $hydratorManager */
        $hydratorManager = $container->get('HydratorManager');

        /** @var InputFilterPluginManager $inputFilterManager */
        $inputFilterManager = $container->get('InputFilterManager');

        /** @var AdvertRepositoryInterface $advertRepository */
        $advertRepository = $container->get(
            AdvertRepositoryInterface::class
        );

        /** @var AdvertHydrator $advertHydrator */
        $advertHydrator = $hydratorManager->get(AdvertHydrator::class);

        /** @var InputFilterInterface $advertInputFilter */
        $advertInputFilter = $inputFilterManager->get(
            AdvertInputFilter::class
        );

        $controller = new RestController();
        $controller->setAdvertRepository($advertRepository);
        $controller->setAdvertHydrator($advertHydrator);
        $controller->setAdvertInputFilter($advertInputFilter);

        return $controller;
    }
}
