<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontend\Authentication;

use Application\Controller\IndexController;
use UserFrontend\Form\UserLoginFormInterface;
use UserModel\Entity\UserEntity;
use UserModel\Hydrator\UserHydrator;
use Zend\Authentication\Adapter\DbTable\AbstractAdapter;
use Zend\Authentication\Adapter\ValidatableAdapterInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Authentication\Result;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\MvcEvent;

/**
 * Class AuthenticationListener
 *
 * @package UserFrontend\Authentication
 */
class AuthenticationListener extends AbstractListenerAggregate
    implements AuthenticationListenerInterface
{
    /**
     * @var AuthenticationServiceInterface|AuthenticationService
     */
    private $authService;

    /**
     * @var UserLoginFormInterface
     */
    private $userLoginForm;

    /**
     * @var UserHydrator
     */
    private $userHydrator;

    /**
     * AuthenticationListener constructor.
     *
     * @param AuthenticationServiceInterface $authService
     * @param UserLoginFormInterface         $userLoginForm
     * @param UserHydrator                   $userHydrator
     */
    public function __construct(
        AuthenticationServiceInterface $authService,
        UserLoginFormInterface $userLoginForm,
        UserHydrator $userHydrator
    ) {
        $this->authService   = $authService;
        $this->userLoginForm = $userLoginForm;
        $this->userHydrator  = $userHydrator;
    }

    /**
     * @param EventManagerInterface $events
     * @param int                   $priority
     *
     * @return mixed
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_ROUTE, [$this, 'authenticate'], -2000
        );
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_ROUTE, [$this, 'logout'], -1000
        );
    }

    /**
     * Authenticate user
     *
     * @param MvcEvent $e
     */
    public function authenticate(MvcEvent $e)
    {
        if ($this->authService->hasIdentity()) {
            return;
        }

        /** @var Request $request */
        $request = $e->getRequest();

        if (!$request->isPost()) {
            return;
        }

        if (!$request->getPost('login_user')) {
            return;
        }

        $userForm = $this->userLoginForm;
        $userForm->setData($request->getPost());

        if (!$userForm->isValid()) {
            return;
        }

        /** @var ValidatableAdapterInterface|AbstractAdapter $authAdapter */
        $authAdapter = $this->authService->getAdapter();
        $authAdapter->setIdentity($userForm->getData()['email']);
        $authAdapter->setCredential($userForm->getData()['password']);

        $result = $this->authService->authenticate();

        if (!$result->isValid()) {
            switch ($result->getCode()) {
                case Result::FAILURE_IDENTITY_NOT_FOUND:
                    $userForm->get('email')->setMessages(
                        ['user_frontend_auth_identity_wrong']
                    );
                    break;

                case Result::FAILURE_CREDENTIAL_INVALID:
                    $userForm->get('password')->setMessages(
                        ['user_frontend_auth_credential_wrong']
                    );
                    break;
            }
        } else {
            $user = new UserEntity();

            $this->userHydrator->hydrate(
                (array)$authAdapter->getResultRowObject(
                    null, ['password']
                ),
                $user
            );

            $this->authService->getStorage()->write($user);

            $routeMatch = $e->getRouteMatch();
            $routeMatch->setParam('controller', IndexController::class);
            $routeMatch->setParam('action', 'index');
        }
    }

    /**
     * Logout user
     *
     * @param MvcEvent $e
     */
    public function logout(MvcEvent $e)
    {
        if (!$this->authService->hasIdentity()) {
            return;
        }

        /** @var Request $request */
        $request = $e->getRequest();

        if (!$request->isPost()) {
            return;
        }

        if (!$request->getPost('logout_user')) {
            return;
        }

        $this->authService->clearIdentity();

        $routeMatch = $e->getRouteMatch();
        $routeMatch->setParam('controller', IndexController::class);
        $routeMatch->setParam('action', 'index');
    }
}
