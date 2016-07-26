<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertFrontendTest\Controller;

use AdvertFrontend\Controller\DisplayController;
use AdvertFrontend\Controller\DisplayControllerFactory;
use AdvertModel\Repository\AdvertRepositoryInterface;
use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase;
use Prophecy\Prophecy\MethodProphecy;

/**
 * Class DisplayControllerFactoryTest
 *
 * @package AdvertFrontendTest\Controller
 */
class DisplayControllerFactoryTest extends PHPUnit_Framework_TestCase
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
        /** @var AdvertRepositoryInterface $advertRepository */
        $advertRepository = $this->prophesize(
            AdvertRepositoryInterface::class
        );

        /** @var ContainerInterface $container */
        $container = $this->prophesize(ContainerInterface::class);
        $container->get(AdvertRepositoryInterface::class)
            ->willReturn($advertRepository)
            ->shouldBeCalled();

        $factory = new DisplayControllerFactory();

        /** @var DisplayController $controller */
        $controller = $factory(
            $container->reveal(), DisplayController::class
        );

        $this->assertTrue($controller instanceof DisplayController);

        $this->assertAttributeEquals(
            $advertRepository->reveal(), 'advertRepository', $controller
        );
    }
}
