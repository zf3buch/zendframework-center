<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Application\Controller;

use AdvertModel\Storage\Db\AdvertDbStorage;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class TestControllerFactory
 *
 * @package Application\Controller
 */
class TestControllerFactory implements FactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ) {
        $advertDbStorage = $container->get(
            AdvertDbStorage::class
        );

        $controller = new TestController();
        $controller->setAdvertDbStorage($advertDbStorage);

        return $controller;
    }
}
