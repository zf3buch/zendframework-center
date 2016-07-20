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

namespace AdvertBackendTest\Controller;

use AdvertBackend\Controller\ModifyController;
use AdvertBackend\Controller\ModifyControllerFactory;
use AdvertBackend\Form\AdvertForm;
use AdvertModel\Repository\AdvertRepositoryInterface;
use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase;

/**
 * Class ModifyControllerFactoryTest
 *
 * @package AdvertBackendTest\Controller
 */
class ModifyControllerFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test factory
     *
     * @group controller
     * @group factory
     * @group advert-backend
     */
    public function testFactory()
    {
        /** @var ContainerInterface $formElementManager */
        $formElementManager = $this->prophesize(ContainerInterface::class);

        /** @var AdvertForm $advertForm */
        $advertForm = $this->prophesize(AdvertForm::class);

        $formElementManager->get(AdvertForm::class)
            ->willReturn($advertForm)
            ->shouldBeCalled();

        /** @var ContainerInterface $container */
        $container = $this->prophesize(ContainerInterface::class);

        /** @var AdvertRepositoryInterface $advertRepository */
        $advertRepository = $this->prophesize(
            AdvertRepositoryInterface::class
        );

        $container->get(AdvertRepositoryInterface::class)
            ->willReturn($advertRepository)
            ->shouldBeCalled();

        $container->get('FormElementManager')
            ->willReturn($formElementManager)
            ->shouldBeCalled();

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

        $this->assertAttributeEquals(
            $advertForm->reveal(), 'advertForm', $controller
        );
    }
}
