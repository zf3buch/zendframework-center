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
use UserFrontend\Controller\IndexController;
use UserFrontend\Controller\IndexControllerFactory;
use UserFrontend\Form\UserEditForm;
use UserFrontend\Form\UserEditFormInterface;
use UserModel\Repository\UserFormInterface;

/**
 * Class IndexControllerFactoryTest
 *
 * @package UserFrontendTest\Controller
 */
class IndexControllerFactoryTest extends PHPUnit_Framework_TestCase
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

        /** @var ContainerInterface $container */
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('FormElementManager')
            ->willReturn($formElementManager)
            ->shouldBeCalled();

        $factory = new IndexControllerFactory();

        /** @var IndexController $controller */
        $controller = $factory(
            $container->reveal(), IndexController::class
        );

        $this->assertTrue($controller instanceof IndexController);

        $this->assertAttributeEquals(
            $userForm->reveal(), 'userForm', $controller
        );
    }
}
