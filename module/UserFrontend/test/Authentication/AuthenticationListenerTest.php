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
     * @group user-frontend
     */
    public function testAttach()
    {
        $events = $this->prophesize(EventManagerInterface::class);

        $events->attach(
            MvcEvent::EVENT_ROUTE,
            [$this->authenticationListener, 'authenticate'],
            -2000
        )->willReturn(
            [$this->authenticationListener, 'authenticate']
        )->shouldBeCalled();

        $events->attach(
            MvcEvent::EVENT_ROUTE,
            [$this->authenticationListener, 'logout'],
            -1000
        )->willReturn(
            [$this->authenticationListener, 'logout']
        )->shouldBeCalled();

        $this->authenticationListener->attach($events->reveal());
    }

    /**
     * Test authenticate with identity
     *
     * @group listener
     * @group user-frontend
     */
    public function testAuthenticateWithIdentity()
    {
        $this->authService->hasIdentity()
            ->willReturn(true)
            ->shouldBeCalled();

        $request = $this->prophesize(Request::class);

        $mvcEvent = $this->prophesize(MvcEvent::class);
        $mvcEvent->getRequest()
            ->willReturn($request)
            ->shouldNotBeCalled();

        $this->authenticationListener->authenticate($mvcEvent->reveal());
    }

    /**
     * Test authenticate no post
     *
     * @group listener
     * @group user-frontend
     */
    public function testAuthenticateNoPost()
    {
        $this->authService->hasIdentity()
            ->willReturn(false)
            ->shouldBeCalled();

        $request = $this->prophesize(Request::class);
        $request->isPost()->willReturn(false)->shouldBeCalled();
        $request->getPost('login_user')
            ->willReturn(false)
            ->shouldNotBeCalled();

        $mvcEvent = $this->prophesize(MvcEvent::class);
        $mvcEvent->getRequest()->willReturn($request)->shouldBeCalled();

        $this->authenticationListener->authenticate($mvcEvent->reveal());
    }

    /**
     * Test authenticate post wrong button
     *
     * @group listener
     * @group user-frontend
     */
    public function testAuthenticateWithPostWrongButton()
    {
        $this->authService->hasIdentity()
            ->willReturn(false)
            ->shouldBeCalled();

        $request = $this->prophesize(Request::class);
        $request->isPost()->willReturn(true)->shouldBeCalled();
        $request->getPost('login_user')
            ->willReturn(false)
            ->shouldBeCalled();
        $request->getPost()->willReturn(false)->shouldNotBeCalled();

        $mvcEvent = $this->prophesize(MvcEvent::class);
        $mvcEvent->getRequest()->willReturn($request)->shouldBeCalled();

        $this->authenticationListener->authenticate($mvcEvent->reveal());
    }

    /**
     * Test authenticate post invalid form
     *
     * @group listener
     * @group user-frontend
     */
    public function testAuthenticateWithPostInvalidForm()
    {
        $postData = [
            'email'      => 'Email',
            'password'   => 'password',
            'login_user' => 'login_user',
        ];

        $this->authService->hasIdentity()
            ->willReturn(false)
            ->shouldBeCalled();

        $request = $this->prophesize(Request::class);
        $request->isPost()->willReturn(true)->shouldBeCalled();
        $request->getPost('login_user')
            ->willReturn(true)
            ->shouldBeCalled();
        $request->getPost()->willReturn($postData)->shouldBeCalled();

        $this->userLoginForm->setData($postData)->shouldBeCalled();
        $this->userLoginForm->isValid()
            ->willReturn(false)
            ->shouldBeCalled();

        $this->authService->getAdapter()->shouldNotBeCalled();

        $mvcEvent = $this->prophesize(MvcEvent::class);
        $mvcEvent->getRequest()->willReturn($request)->shouldBeCalled();

        $this->authenticationListener->authenticate($mvcEvent->reveal());
    }

    /**
     * Test authenticate post valid result
     *
     * @group listener
     * @group user-frontend
     */
    public function testAuthenticateWithPostValidResult()
    {
        $postData = [
            'email'      => 'Email',
            'password'   => 'password',
            'login_user' => 'login_user',
        ];

        $this->authService->hasIdentity()
            ->willReturn(false)
            ->shouldBeCalled();

        $request = $this->prophesize(Request::class);
        $request->isPost()->willReturn(true)->shouldBeCalled();
        $request->getPost('login_user')
            ->willReturn(true)
            ->shouldBeCalled();
        $request->getPost()->willReturn($postData)->shouldBeCalled();

        $this->userLoginForm->setData($postData)->shouldBeCalled();
        $this->userLoginForm->isValid()
            ->willReturn(true)
            ->shouldBeCalled();
        $this->userLoginForm->getData()
            ->willReturn($postData)
            ->shouldBeCalled();

        $this->authService->getAdapter()
            ->willReturn($this->authAdapter)
            ->shouldBeCalled();
        $this->authService->authenticate()
            ->willReturn($this->authResult)
            ->shouldBeCalled();
        $this->authService->getStorage()
            ->willReturn($this->authStorage)
            ->shouldBeCalled();

        $this->authAdapter->setIdentity($postData['email'])
            ->shouldBeCalled();
        $this->authAdapter->setCredential($postData['password'])
            ->shouldBeCalled();
        $this->authAdapter->getResultRowObject(
            null, ['password']
        )->shouldBeCalled();

        $this->authResult->isValid()
            ->willReturn(true)
            ->shouldBeCalled();

        $user = new UserEntity();

        $this->authStorage->write($user)
            ->willReturn(true)
            ->shouldBeCalled();

        $mvcEvent = $this->prophesize(MvcEvent::class);
        $mvcEvent->getRequest()
            ->willReturn($request)
            ->shouldBeCalled();

        $this->authenticationListener->authenticate($mvcEvent->reveal());
    }
}
