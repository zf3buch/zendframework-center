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
     */
    public function testAttach()
    {
        $events = $this->prophesize(EventManagerInterface::class);

        /** @var MethodProphecy $method */
        $method = $events->attach(
            MvcEvent::EVENT_ROUTE,
            [$this->authorizationListener, 'authorize'],
            -3000
        );
        $method->willReturn(
            [$this->authorizationListener, 'authorize']
        );
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $events->attach(
            MvcEvent::EVENT_DISPATCH,
            [$this->authorizationListener, 'prepareNavigation'],
            -1000
        );
        $method->willReturn(
            [$this->authorizationListener, 'prepareNavigation']
        );
        $method->shouldBeCalled();

        $this->authorizationListener->attach($events->reveal());
    }

    /**
     * Test authorize guest success
     */
    public function testAuthorizeGuestSuccess()
    {
        $role       = 'guest';
        $controller = IndexController::class;
        $action     = 'index';

        $routeMatch = $this->prophesize(RouteMatch::class);

        /** @var MethodProphecy $method */
        $method = $routeMatch->getParam('controller');
        $method->willReturn($controller);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $routeMatch->getParam('action');
        $method->willReturn($action);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $routeMatch->setParam('controller');
        $method->shouldNotBeCalled();

        /** @var MethodProphecy $method */
        $method = $routeMatch->setParam('action');
        $method->shouldNotBeCalled();

        /** @var MethodProphecy $method */
        $method = $this->userAcl->isAllowed(
            $role, 'application-index', $action
        );
        $method->willReturn(true);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $this->authService->getIdentity();
        $method->willReturn(false);
        $method->shouldBeCalled();

        $mvcEvent = $this->prophesize(MvcEvent::class);

        /** @var MethodProphecy $method */
        $method = $mvcEvent->getRouteMatch();
        $method->willReturn($routeMatch);
        $method->shouldBeCalled();

        $this->authorizationListener->authorize($mvcEvent->reveal());
    }

    /**
     * Test authorize guest failed
     */
    public function testAuthorizeGuestFailed()
    {
        $role       = 'guest';
        $controller = DisplayController::class;
        $action     = 'index';

        $routeMatch = $this->prophesize(RouteMatch::class);

        /** @var MethodProphecy $method */
        $method = $routeMatch->getParam('controller');
        $method->willReturn($controller);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $routeMatch->getParam('action');
        $method->willReturn($action);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $routeMatch->setParam(
            'controller', ForbiddenController::class
        );
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $routeMatch->setParam('action', 'index');
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $this->userAcl->isAllowed(
            $role, 'user-backend-display', $action
        );
        $method->willReturn(false);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $this->authService->getIdentity();
        $method->willReturn(false);
        $method->shouldBeCalled();

        $mvcEvent = $this->prophesize(MvcEvent::class);

        /** @var MethodProphecy $method */
        $method = $mvcEvent->getRouteMatch();
        $method->willReturn($routeMatch);
        $method->shouldBeCalled();

        $this->authorizationListener->authorize($mvcEvent->reveal());
    }

    /**
     * Test authorize admin success
     */
    public function testAuthorizeAdminSuccess()
    {
        $role       = 'admin';
        $controller = IndexController::class;
        $action     = 'index';

        $routeMatch = $this->prophesize(RouteMatch::class);

        /** @var MethodProphecy $method */
        $method = $routeMatch->getParam('controller');
        $method->willReturn($controller);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $routeMatch->getParam('action');
        $method->willReturn($action);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $routeMatch->setParam('controller');
        $method->shouldNotBeCalled();

        /** @var MethodProphecy $method */
        $method = $routeMatch->setParam('action');
        $method->shouldNotBeCalled();

        /** @var MethodProphecy $method */
        $method = $this->userAcl->isAllowed(
            $role, 'application-index', $action
        );
        $method->willReturn(true);
        $method->shouldBeCalled();

        $identity = new UserEntity();
        $identity->setRole($role);

        /** @var MethodProphecy $method */
        $method = $this->authService->getIdentity();
        $method->willReturn($identity);
        $method->shouldBeCalled();

        $mvcEvent = $this->prophesize(MvcEvent::class);

        /** @var MethodProphecy $method */
        $method = $mvcEvent->getRouteMatch();
        $method->willReturn($routeMatch);
        $method->shouldBeCalled();

        $this->authorizationListener->authorize($mvcEvent->reveal());
    }

    /**
     * Test authorize admin failed
     */
    public function testAuthorizeAdminFailed()
    {
        $role       = 'admin';
        $controller = DisplayController::class;
        $action     = 'index';

        $routeMatch = $this->prophesize(RouteMatch::class);

        /** @var MethodProphecy $method */
        $method = $routeMatch->getParam('controller');
        $method->willReturn($controller);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $routeMatch->getParam('action');
        $method->willReturn($action);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $routeMatch->setParam(
            'controller', ForbiddenController::class
        );
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $routeMatch->setParam('action', 'index');
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $this->userAcl->isAllowed(
            $role, 'user-backend-display', $action
        );
        $method->willReturn(false);
        $method->shouldBeCalled();

        $identity = new UserEntity();
        $identity->setRole($role);

        /** @var MethodProphecy $method */
        $method = $this->authService->getIdentity();
        $method->willReturn($identity);
        $method->shouldBeCalled();

        $mvcEvent = $this->prophesize(MvcEvent::class);

        /** @var MethodProphecy $method */
        $method = $mvcEvent->getRouteMatch();
        $method->willReturn($routeMatch);
        $method->shouldBeCalled();

        $this->authorizationListener->authorize($mvcEvent->reveal());
    }

    /**
     * Test prepare navigation
     */
    public function testPrepareNavigation()
    {
        $role = 'company';

        $identity = new UserEntity();
        $identity->setRole($role);

        /** @var MethodProphecy $method */
        $method = $this->authService->getIdentity();
        $method->willReturn($identity);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $this->navigationHelper->setRole($role);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $this->navigationHelper->setAcl(
            $this->userAcl->reveal()
        );
        $method->shouldBeCalled();

        $mvcEvent = $this->prophesize(MvcEvent::class);

        $this->authorizationListener->prepareNavigation(
            $mvcEvent->reveal()
        );
    }
}
