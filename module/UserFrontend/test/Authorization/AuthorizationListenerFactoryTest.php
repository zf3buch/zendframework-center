<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontendTest\Authorization;

use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase;
use Prophecy\Prophecy\MethodProphecy;
use UserFrontend\Authorization\AuthorizationListener;
use UserFrontend\Authorization\AuthorizationListenerFactory;
use UserFrontend\Form\UserLoginForm;
use UserModel\Hydrator\UserHydrator;
use UserModel\Permissions\UserAcl;
use Zend\Authentication\AuthenticationService;
use Zend\View\Helper\Navigation;

/**
 * Class AuthorizationListenerFactoryTest
 *
 * @package UserFrontendTest\Authorization
 */
class AuthorizationListenerFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test factory
     *
     * @group listener
     * @group factory
     */
    public function testFactory()
    {
        /** @var Navigation $navigationViewHelper */
        $navigationViewHelper = $this->prophesize(Navigation::class);

        /** @var ContainerInterface $viewHelperManager */
        $viewHelperManager = $this->prophesize(ContainerInterface::class);

        /** @var MethodProphecy $method */
        $method = $viewHelperManager->get(Navigation::class);
        $method->willReturn($navigationViewHelper);
        $method->shouldBeCalled();

        /** @var AuthenticationService $authenticationService */
        $authenticationService = $this->prophesize(
            AuthenticationService::class
        );

        /** @var UserAcl $userAcl */
        $userAcl = $this->prophesize(UserAcl::class);

        /** @var ContainerInterface $container */
        $container = $this->prophesize(ContainerInterface::class);

        /** @var MethodProphecy $method */
        $method = $container->get(AuthenticationService::class);
        $method->willReturn($authenticationService);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $container->get(UserAcl::class);
        $method->willReturn($userAcl);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $container->get('ViewHelperManager');
        $method->willReturn($viewHelperManager);
        $method->shouldBeCalled();

        $factory = new AuthorizationListenerFactory();

        $this->assertTrue(
            $factory instanceof AuthorizationListenerFactory
        );

        /** @var AuthorizationListener $table */
        $listener = $factory(
            $container->reveal(), AuthorizationListener::class
        );

        $this->assertTrue($listener instanceof AuthorizationListener);

        $this->assertAttributeEquals(
            $authenticationService->reveal(), 'authService', $listener
        );
        $this->assertAttributeEquals(
            $userAcl->reveal(), 'userAcl', $listener
        );
        $this->assertAttributeEquals(
            $navigationViewHelper->reveal(), 'navigationHelper', $listener
        );
    }
}
