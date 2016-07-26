<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontendTest\Controller;

use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase;
use UserFrontend\Controller\EditController;
use UserFrontend\Controller\EditControllerFactory;
use UserFrontend\Form\UserEditForm;
use UserFrontend\Form\UserEditFormInterface;
use UserModel\Repository\UserRepositoryInterface;

/**
 * Class EditControllerFactoryTest
 *
 * @package UserFrontendTest\Controller
 */
class EditControllerFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test factory
     *
     * @group controller
     * @group factory
     * @group user-frontend
     */
    public function testFactory()
    {
        /** @var UserEditFormInterface $userForm */
        $userForm = $this->prophesize(
            UserEditFormInterface::class
        );

        /** @var ContainerInterface $formElementManager */
        $formElementManager = $this->prophesize(ContainerInterface::class);
        $formElementManager->get(UserEditForm::class)
            ->willReturn($userForm)
            ->shouldBeCalled();

        /** @var UserRepositoryInterface $userRepository */
        $userRepository = $this->prophesize(
            UserRepositoryInterface::class
        );

        /** @var ContainerInterface $container */
        $container = $this->prophesize(ContainerInterface::class);
        $container->get(UserRepositoryInterface::class)
            ->willReturn($userRepository)
            ->shouldBeCalled();
        $container->get('FormElementManager')
            ->willReturn($formElementManager)
            ->shouldBeCalled();

        $factory = new EditControllerFactory();

        /** @var EditController $controller */
        $controller = $factory(
            $container->reveal(), EditController::class
        );

        $this->assertTrue($controller instanceof EditController);

        $this->assertAttributeEquals(
            $userForm->reveal(), 'userForm', $controller
        );
    }
}
