<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserBackendTest\Controller;

use UserBackend\Controller\ModifyController;
use UserBackend\Controller\ModifyControllerFactory;
use UserBackend\Form\UserForm;
use UserModel\Repository\UserRepositoryInterface;
use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase;

/**
 * Class ModifyControllerFactoryTest
 *
 * @package UserBackendTest\Controller
 */
class ModifyControllerFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test factory
     *
     * @group controller
     * @group factory
     * @group user-backend
     */
    public function testFactory()
    {
        /** @var ContainerInterface $formElementManager */
        $formElementManager = $this->prophesize(ContainerInterface::class);

        /** @var UserForm $userForm */
        $userForm = $this->prophesize(UserForm::class);

        $formElementManager->get(UserForm::class)
            ->willReturn($userForm)
            ->shouldBeCalled();

        /** @var ContainerInterface $container */
        $container = $this->prophesize(ContainerInterface::class);

        /** @var UserRepositoryInterface $userRepository */
        $userRepository = $this->prophesize(
            UserRepositoryInterface::class
        );

        $container->get(UserRepositoryInterface::class)
            ->willReturn($userRepository)
            ->shouldBeCalled();

        $container->get('FormElementManager')
            ->willReturn($formElementManager)
            ->shouldBeCalled();

        $factory = new ModifyControllerFactory();

        /** @var ModifyController $controller */
        $controller = $factory(
            $container->reveal(), ModifyController::class
        );

        $this->assertTrue($controller instanceof ModifyController);

        $this->assertAttributeEquals(
            $userRepository->reveal(), 'userRepository', $controller
        );

        $this->assertAttributeEquals(
            $userForm->reveal(), 'userForm', $controller
        );
    }
}
