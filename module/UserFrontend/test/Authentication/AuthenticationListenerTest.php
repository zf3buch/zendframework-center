<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontendTest\Authentication;

use PHPUnit_Framework_TestCase;
use Prophecy\Prophecy\MethodProphecy;
use UserFrontend\Authentication\AuthenticationListener;
use UserFrontend\Form\UserLoginForm;
use UserModel\Entity\UserEntity;
use UserModel\Hydrator\UserHydrator;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Adapter\DbTable\AbstractAdapter;
use Zend\Authentication\Adapter\ValidatableAdapterInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Zend\Authentication\Storage\StorageInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\MvcEvent;

/**
 * Class AuthenticationListenerTest
 *
 * @package UserFrontendTest\Authentication
 */
class AuthenticationListenerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var AuthenticationListener
     */
    private $authenticationListener;

    /**
     * @var AuthenticationService
     */
    private $authService;

    /**
     * @var UserLoginForm
     */
    private $userLoginForm;

    /**
     * @var UserHydrator
     */
    private $userHydrator;

    /**
     * @var AdapterInterface|ValidatableAdapterInterface|AbstractAdapter
     */
    protected $authAdapter;

    /**
     * @var StorageInterface
     */
    protected $authStorage;

    /**
     * @var Result
     */
    protected $authResult;

    /**
     * Setup test cases
     */
    protected function setUp()
    {
        $this->authService   = $this->prophesize(
            AuthenticationService::class
        );
        $this->userLoginForm = $this->prophesize(UserLoginForm::class);
        $this->userHydrator  = $this->prophesize(UserHydrator::class);
        $this->authAdapter   = $this->prophesize(AbstractAdapter::class);
        $this->authStorage   = $this->prophesize(StorageInterface::class);
        $this->authResult    = $this->prophesize(Result::class);

        $this->authenticationListener = new AuthenticationListener(
            $this->authService->reveal(),
            $this->userLoginForm->reveal(),
            $this->userHydrator->reveal()
        );
    }

    /**
     * Test attaching listeners
     *
     * @group listener
     */
    public function testAttach()
    {
        $events = $this->prophesize(EventManagerInterface::class);

        /** @var MethodProphecy $method */
        $method = $events->attach(
            MvcEvent::EVENT_ROUTE,
            [$this->authenticationListener, 'authenticate'],
            -2000
        );
        $method->willReturn(
            [$this->authenticationListener, 'authenticate']
        );
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $events->attach(
            MvcEvent::EVENT_ROUTE,
            [$this->authenticationListener, 'logout'],
            -1000
        );
        $method->willReturn([$this->authenticationListener, 'logout']);
        $method->shouldBeCalled();

        $this->authenticationListener->attach($events->reveal());
    }

    /**
     * Test authenticate with identity
     *
     * @group listener
     */
    public function testAuthenticateWithIdentity()
    {
        /** @var MethodProphecy $method */
        $method = $this->authService->hasIdentity();
        $method->willReturn(true);
        $method->shouldBeCalled();

        $request = $this->prophesize(Request::class);

        $mvcEvent = $this->prophesize(MvcEvent::class);

        /** @var MethodProphecy $method */
        $method = $mvcEvent->getRequest();
        $method->willReturn($request);
        $method->shouldNotBeCalled();

        $this->authenticationListener->authenticate($mvcEvent->reveal());
    }

    /**
     * Test authenticate no post
     *
     * @group listener
     */
    public function testAuthenticateNoPost()
    {
        /** @var MethodProphecy $method */
        $method = $this->authService->hasIdentity();
        $method->willReturn(false);
        $method->shouldBeCalled();

        $request = $this->prophesize(Request::class);

        /** @var MethodProphecy $method */
        $method = $request->isPost();
        $method->willReturn(false);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $request->getPost('login_user');
        $method->willReturn(false);
        $method->shouldNotBeCalled();

        $mvcEvent = $this->prophesize(MvcEvent::class);

        /** @var MethodProphecy $method */
        $method = $mvcEvent->getRequest();
        $method->willReturn($request);
        $method->shouldBeCalled();

        $this->authenticationListener->authenticate($mvcEvent->reveal());
    }

    /**
     * Test authenticate post wrong button
     *
     * @group listener
     */
    public function testAuthenticateWithPostWrongButton()
    {
        /** @var MethodProphecy $method */
        $method = $this->authService->hasIdentity();
        $method->willReturn(false);
        $method->shouldBeCalled();

        $request = $this->prophesize(Request::class);

        /** @var MethodProphecy $method */
        $method = $request->isPost();
        $method->willReturn(true);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $request->getPost('login_user');
        $method->willReturn(false);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $request->getPost();
        $method->willReturn(false);
        $method->shouldNotBeCalled();

        $mvcEvent = $this->prophesize(MvcEvent::class);

        /** @var MethodProphecy $method */
        $method = $mvcEvent->getRequest();
        $method->willReturn($request);
        $method->shouldBeCalled();

        $this->authenticationListener->authenticate($mvcEvent->reveal());
    }

    /**
     * Test authenticate post invalid form
     *
     * @group listener
     */
    public function testAuthenticateWithPostInvalidForm()
    {
        $postData = [
            'email'      => 'Email',
            'password'   => 'password',
            'login_user' => 'login_user',
        ];

        /** @var MethodProphecy $method */
        $method = $this->authService->hasIdentity();
        $method->willReturn(false);
        $method->shouldBeCalled();

        $request = $this->prophesize(Request::class);

        /** @var MethodProphecy $method */
        $method = $request->isPost();
        $method->willReturn(true);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $request->getPost('login_user');
        $method->willReturn(true);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $request->getPost();
        $method->willReturn($postData);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $this->userLoginForm->setData($postData);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $this->userLoginForm->isValid();
        $method->willReturn(false);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $this->authService->getAdapter();
        $method->shouldNotBeCalled();

        $mvcEvent = $this->prophesize(MvcEvent::class);

        /** @var MethodProphecy $method */
        $method = $mvcEvent->getRequest();
        $method->willReturn($request);
        $method->shouldBeCalled();

        $this->authenticationListener->authenticate($mvcEvent->reveal());
    }

    /**
     * Test authenticate post valid result
     *
     * @group listener
     */
    public function testAuthenticateWithPostValidResult()
    {
        $postData = [
            'email'      => 'Email',
            'password'   => 'password',
            'login_user' => 'login_user',
        ];

        /** @var MethodProphecy $method */
        $method = $this->authService->hasIdentity();
        $method->willReturn(false);
        $method->shouldBeCalled();

        $request = $this->prophesize(Request::class);

        /** @var MethodProphecy $method */
        $method = $request->isPost();
        $method->willReturn(true);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $request->getPost('login_user');
        $method->willReturn(true);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $request->getPost();
        $method->willReturn($postData);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $this->userLoginForm->setData($postData);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $this->userLoginForm->isValid();
        $method->willReturn(true);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $this->userLoginForm->getData();
        $method->willReturn($postData);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $this->authService->getAdapter();
        $method->willReturn($this->authAdapter);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $this->authService->authenticate();
        $method->willReturn($this->authResult);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $this->authService->getStorage();
        $method->willReturn($this->authStorage);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $this->authAdapter->setIdentity($postData['email']);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $this->authAdapter->setCredential($postData['password']);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $this->authAdapter->getResultRowObject(
            null, ['password']
        );
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $this->authResult->isValid();
        $method->willReturn(true);
        $method->shouldBeCalled();

        $user = new UserEntity();

        /** @var MethodProphecy $method */
        $method = $this->authStorage->write($user);
        $method->willReturn(true);
        $method->shouldBeCalled();

        $mvcEvent = $this->prophesize(MvcEvent::class);

        /** @var MethodProphecy $method */
        $method = $mvcEvent->getRequest();
        $method->willReturn($request);
        $method->shouldBeCalled();

        $this->authenticationListener->authenticate($mvcEvent->reveal());
    }
}
