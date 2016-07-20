<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontendTest\Authentication;

use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase;
use Prophecy\Prophecy\MethodProphecy;
use UserFrontend\Authentication\AuthenticationListener;
use UserFrontend\Authentication\AuthenticationListenerFactory;
use UserFrontend\Form\UserLoginForm;
use UserModel\Hydrator\UserHydrator;
use Zend\Authentication\AuthenticationService;

/**
 * Class AuthenticationListenerFactoryTest
 *
 * @package UserFrontendTest\Authentication
 */
class AuthenticationListenerFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test factory
     *
     * @group listener
     * @group factory
     * @group user-frontend
     */
    public function testFactory()
    {
        $userLoginForm = new UserLoginForm();
        $userHydrator  = new UserHydrator();

        /** @var ContainerInterface $formElementManager */
        $formElementManager = $this->prophesize(ContainerInterface::class);
        $formElementManager->get(UserLoginForm::class)
            ->willReturn($userLoginForm)
            ->shouldBeCalled();

        /** @var ContainerInterface $hydratorManager */
        $hydratorManager = $this->prophesize(ContainerInterface::class);
        $hydratorManager->get(UserHydrator::class)
            ->willReturn($userHydrator)
            ->shouldBeCalled();

        /** @var AuthenticationService $authenticationService */
        $authenticationService = $this->prophesize(
            AuthenticationService::class
        );

        /** @var ContainerInterface $container */
        $container = $this->prophesize(ContainerInterface::class);
        $container->get(AuthenticationService::class)
            ->willReturn($authenticationService)
            ->shouldBeCalled();
        $container->get('FormElementManager')
            ->willReturn($formElementManager)
            ->shouldBeCalled();
        $container->get('HydratorManager')
            ->willReturn($hydratorManager)
            ->shouldBeCalled();

        $factory = new AuthenticationListenerFactory();

        $this->assertTrue(
            $factory instanceof AuthenticationListenerFactory
        );

        /** @var AuthenticationListener $table */
        $listener = $factory(
            $container->reveal(), AuthenticationListener::class
        );

        $this->assertTrue($listener instanceof AuthenticationListener);

        $this->assertAttributeEquals(
            $authenticationService->reveal(), 'authService', $listener
        );
        $this->assertAttributeEquals(
            $userLoginForm, 'userLoginForm', $listener
        );
        $this->assertAttributeEquals(
            $userHydrator, 'userHydrator', $listener
        );
    }
}
