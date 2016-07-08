<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontend\Authentication;

use Zend\Authentication\AuthenticationServiceInterface;
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
     * @var AuthenticationServiceInterface
     */
    private $authService;

    /**
     * AuthenticationListener constructor.
     *
     * @param AuthenticationServiceInterface $authService
     */
    public function __construct(
        AuthenticationServiceInterface $authService
    ) {
        $this->authService = $authService;
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
            MvcEvent::EVENT_ROUTE, [$this, 'authenticate'], -1000
        );
    }

    /**
     * Authenticate user
     *
     * @param MvcEvent $e
     */
    public function authenticate(MvcEvent $e)
    {
        /** @var Request $request */
        $request = $e->getRequest();

        var_dump($request->isPost());
    }
}
