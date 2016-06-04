<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertFrontend\Controller;

use AdvertModel\Repository\AdvertRepositoryInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class DisplayControllerFactory
 *
 * @package AdvertFrontend\Controller
 */
class DisplayControllerFactory implements FactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ) {
        $advertRepository = $container->get(
            AdvertRepositoryInterface::class
        );

        $controller = new DisplayController();
        $controller->setAdvertRepository($advertRepository);

        return $controller;
    }
}
