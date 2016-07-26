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

namespace UserFrontendTest\Controller;

use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase;
use UserFrontend\Controller\RegisterController;
use UserFrontend\Controller\RegisterControllerFactory;
use UserFrontend\Form\UserRegisterForm;
use UserFrontend\Form\UserRegisterFormInterface;
use UserModel\Repository\UserRepositoryInterface;

/**
 * Class RegisterControllerFactoryTest
 *
 * @package UserFrontendTest\Controller
 */
class RegisterControllerFactoryTest extends PHPUnit_Framework_TestCase
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
        /** @var UserRegisterFormInterface $userForm */
        $userForm = $this->prophesize(
            UserRegisterFormInterface::class
        );

        /** @var ContainerInterface $formElementManager */
        $formElementManager = $this->prophesize(ContainerInterface::class);
        $formElementManager->get(UserRegisterForm::class)
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

        $factory = new RegisterControllerFactory();

        /** @var RegisterController $controller */
        $controller = $factory(
            $container->reveal(), RegisterController::class
        );

        $this->assertTrue($controller instanceof RegisterController);

        $this->assertAttributeEquals(
            $userForm->reveal(), 'userForm', $controller
        );
    }
}
