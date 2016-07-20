<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertFrontendTest\Controller;

use AdvertFrontend\Controller\ModifyController;
use AdvertFrontend\Controller\ModifyControllerFactory;
use AdvertModel\Repository\AdvertRepositoryInterface;
use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase;
use Prophecy\Prophecy\MethodProphecy;

/**
 * Class ModifyControllerFactoryTest
 *
 * @package AdvertFrontendTest\Controller
 */
class ModifyControllerFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test factory
     *
     * @group controller
     * @group factory
     * @group advert-frontend
     */
    public function testFactory()
    {
        /** @var ContainerInterface $container */
        $container = $this->prophesize(ContainerInterface::class);

        /** @var AdvertRepositoryInterface $advertRepository */
        $advertRepository = $this->prophesize(
            AdvertRepositoryInterface::class
        );

        /** @var MethodProphecy $method */
        $method = $container->get(AdvertRepositoryInterface::class);
        $method->willReturn($advertRepository);
        $method->shouldBeCalled();

        $factory = new ModifyControllerFactory();

        $this->assertTrue(
            $factory instanceof ModifyControllerFactory
        );

        /** @var ModifyController $controller */
        $controller = $factory(
            $container->reveal(), ModifyController::class
        );

        $this->assertTrue($controller instanceof ModifyController);

        $this->assertAttributeEquals(
            $advertRepository->reveal(), 'advertRepository', $controller
        );
    }
}
