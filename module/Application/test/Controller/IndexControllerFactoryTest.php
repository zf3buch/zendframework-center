<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace ApplicationTest\Controller;

use AdvertModel\Repository\AdvertRepositoryInterface;
use Application\Controller\IndexController;
use Application\Controller\IndexControllerFactory;
use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase;
use Prophecy\Prophecy\MethodProphecy;

/**
 * Class IndexControllerFactoryTest
 *
 * @package ApplicationTest\Controller
 */
class IndexControllerFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test factory
     *
     * @group controller
     * @group factory
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

        $factory = new IndexControllerFactory();

        $this->assertTrue(
            $factory instanceof IndexControllerFactory
        );

        /** @var IndexController $controller */
        $controller = $factory(
            $container->reveal(), IndexController::class
        );

        $this->assertTrue($controller instanceof IndexController);

        $this->assertAttributeEquals(
            $advertRepository->reveal(), 'advertRepository', $controller
        );
    }
}
