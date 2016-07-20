<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontendTest\Authorization;

use Application\Controller\IndexController;
use PHPUnit_Framework_TestCase;
use Prophecy\Prophecy\MethodProphecy;
use UserBackend\Controller\DisplayController;
use UserFrontend\Authorization\AuthorizationListener;
use UserFrontend\Controller\ForbiddenController;
use UserModel\Entity\UserEntity;
use UserModel\Permissions\UserAcl;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\Router\RouteMatch;
use Zend\View\Helper\Navigation;

/**
 * Class AuthorizationListenerTest
 *
 * @package UserFrontendTest\Authorization
 */
class AuthorizationListenerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var AuthorizationListener
     */
    private $authorizationListener;

    /**
     * @var AuthenticationServiceInterface|AuthenticationService
     */
    private $authService;

    /**
     * @var UserAcl
     */
    private $userAcl;

    /**
     * @var Navigation
     */
    private $navigationHelper;

    /**
     * Setup test cases
     */
    protected function setUp()
    {
        $this->authService      = $this->prophesize(
            AuthenticationService::class
        );
        $this->userAcl          = $this->prophesize(UserAcl::class);
        $this->navigationHelper = $this->prophesize(Navigation::class);

        $this->authorizationListener = new AuthorizationListener(
            $this->authService->reveal(),
            $this->userAcl->reveal(),
            $this->navigationHelper->reveal()
        );
    }

    /**
     * Test attaching listeners
     *
     * @group listener
     * @group user-frontend
     */
    public function testAttach()
    {
        $events = $this->prophesize(EventManagerInterface::class);

        $events->attach(
            MvcEvent::EVENT_ROUTE,
            [$this->authorizationListener, 'authorize'],
            -3000
        )->willReturn(
            [$this->authorizationListener, 'authorize']
        )->shouldBeCalled();

        $events->attach(
            MvcEvent::EVENT_DISPATCH,
            [$this->authorizationListener, 'prepareNavigation'],
            -1000
        )->willReturn(
            [$this->authorizationListener, 'prepareNavigation']
        )->shouldBeCalled();

        $this->authorizationListener->attach($events->reveal());
    }

    /**
     * Test authorize guest success
     *
     * @group listener
     * @group user-frontend
     */
    public function testAuthorizeGuestSuccess()
    {
        $role       = 'guest';
        $controller = IndexController::class;
        $action     = 'index';

        $routeMatch = $this->prophesize(RouteMatch::class);
        $routeMatch->getParam('controller')
            ->willReturn($controller)
            ->shouldBeCalled();
        $routeMatch->getParam('action')
            ->willReturn($action)
            ->shouldBeCalled();
        $routeMatch->setParam('controller')->shouldNotBeCalled();
        $routeMatch->setParam('action')->shouldNotBeCalled();

        $this->userAcl->isAllowed(
            $role, 'application-index', $action
        )->willReturn(true)
            ->shouldBeCalled();

        $this->authService->getIdentity()
            ->willReturn(false)
            ->shouldBeCalled();

        $mvcEvent = $this->prophesize(MvcEvent::class);
        $mvcEvent->getRouteMatch()
            ->willReturn($routeMatch)
            ->shouldBeCalled();

        $this->authorizationListener->authorize($mvcEvent->reveal());
    }

    /**
     * Test authorize guest failed
     *
     * @group listener
     * @group user-frontend
     */
    public function testAuthorizeGuestFailed()
    {
        $role       = 'guest';
        $controller = DisplayController::class;
        $action     = 'index';

        $routeMatch = $this->prophesize(RouteMatch::class);
        $routeMatch->getParam('controller')
            ->willReturn($controller)
            ->shouldBeCalled();
        $routeMatch->getParam('action')
            ->willReturn($action)
            ->shouldBeCalled();
        $routeMatch->setParam(
            'controller', ForbiddenController::class
        )->shouldBeCalled();
        $routeMatch->setParam('action', 'index')->shouldBeCalled();

        $this->userAcl->isAllowed(
            $role, 'user-backend-display', $action
        )->willReturn(false)
            ->shouldBeCalled();

        $this->authService->getIdentity()
            ->willReturn(false)
            ->shouldBeCalled();

        $mvcEvent = $this->prophesize(MvcEvent::class);
        $mvcEvent->getRouteMatch()
            ->willReturn($routeMatch)
            ->shouldBeCalled();

        $this->authorizationListener->authorize($mvcEvent->reveal());
    }

    /**
     * Test authorize admin success
     *
     * @group listener
     * @group user-frontend
     */
    public function testAuthorizeAdminSuccess()
    {
        $role       = 'admin';
        $controller = IndexController::class;
        $action     = 'index';

        $routeMatch = $this->prophesize(RouteMatch::class);
        $routeMatch->getParam('controller')
            ->willReturn($controller)
            ->shouldBeCalled();
        $routeMatch->getParam('action')
            ->willReturn($action)
            ->shouldBeCalled();
        $routeMatch->setParam('controller')->shouldNotBeCalled();
        $routeMatch->setParam('action')->shouldNotBeCalled();

        $this->userAcl->isAllowed(
            $role, 'application-index', $action
        )->willReturn(true)
            ->shouldBeCalled();

        $identity = new UserEntity();
        $identity->setRole($role);

        $this->authService->getIdentity()
            ->willReturn($identity)
            ->shouldBeCalled();

        $mvcEvent = $this->prophesize(MvcEvent::class);
        $mvcEvent->getRouteMatch()
            ->willReturn($routeMatch)
            ->shouldBeCalled();

        $this->authorizationListener->authorize($mvcEvent->reveal());
    }

    /**
     * Test authorize admin failed
     *
     * @group listener
     * @group user-frontend
     */
    public function testAuthorizeAdminFailed()
    {
        $role       = 'admin';
        $controller = DisplayController::class;
        $action     = 'index';

        $routeMatch = $this->prophesize(RouteMatch::class);
        $routeMatch->getParam('controller')
            ->willReturn($controller)
            ->shouldBeCalled();
        $routeMatch->getParam('action')
            ->willReturn($action)
            ->shouldBeCalled();
        $routeMatch->setParam(
            'controller', ForbiddenController::class
        )->shouldBeCalled();
        $routeMatch->setParam('action', 'index')->shouldBeCalled();

        $this->userAcl->isAllowed(
            $role, 'user-backend-display', $action
        )->willReturn(false)
            ->shouldBeCalled();

        $identity = new UserEntity();
        $identity->setRole($role);

        $this->authService->getIdentity()
            ->willReturn($identity)
            ->shouldBeCalled();

        $mvcEvent = $this->prophesize(MvcEvent::class);
        $mvcEvent->getRouteMatch()
            ->willReturn($routeMatch)
            ->shouldBeCalled();

        $this->authorizationListener->authorize($mvcEvent->reveal());
    }

    /**
     * Test prepare navigation
     *
     * @group listener
     * @group user-frontend
     */
    public function testPrepareNavigation()
    {
        $role = 'company';

        $identity = new UserEntity();
        $identity->setRole($role);

        $this->authService->getIdentity()
            ->willReturn($identity)
            ->shouldBeCalled();

        $this->navigationHelper->setRole($role)
            ->shouldBeCalled();

        $this->navigationHelper->setAcl(
            $this->userAcl->reveal()
        )->shouldBeCalled();

        $mvcEvent = $this->prophesize(MvcEvent::class);

        $this->authorizationListener->prepareNavigation(
            $mvcEvent->reveal()
        );
    }
}
