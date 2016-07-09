<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontend\Authorization;

use UserModel\Permissions\Role\GuestRole;
use UserModel\Permissions\UserAcl;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\View\Helper\Navigation;

/**
 * Class AuthorizationListener
 *
 * @package UserFrontend\Authorization
 */
class AuthorizationListener extends AbstractListenerAggregate
    implements AuthorizationListenerInterface
{
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
     * AuthorizationListener constructor.
     *
     * @param AuthenticationServiceInterface $authService
     * @param UserAcl                        $userAcl
     * @param Navigation                     $navigationHelper
     */
    public function __construct(
        $authService, UserAcl $userAcl, Navigation $navigationHelper
    ) {
        $this->authService      = $authService;
        $this->userAcl          = $userAcl;
        $this->navigationHelper = $navigationHelper;
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
            MvcEvent::EVENT_ROUTE, [$this, 'authorize'], -3000
        );
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_DISPATCH, [$this, 'prepareNavigation'], -1000
        );
    }

    /**
     * Authorize user
     *
     * @param MvcEvent $e
     */
    public function authorize(MvcEvent $e)
    {
        var_dump(__METHOD__);
    }

    /**
     * Prepare navigation
     *
     * @param MvcEvent $e
     */
    public function prepareNavigation(MvcEvent $e)
    {
        var_dump(__METHOD__);

        if ($this->authService->getIdentity()) {
            $role = $this->authService->getIdentity()->getRole();
        } else {
            $role = GuestRole::NAME;
        }

        $this->navigationHelper->setRole($role);
        $this->navigationHelper->setAcl($this->userAcl);
    }
}
