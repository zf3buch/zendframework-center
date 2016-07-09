<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontend\Authorization;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;

/**
 * Interface AuthorizationListenerInterface
 *
 * @package UserFrontend\Authorization
 */
interface AuthorizationListenerInterface extends ListenerAggregateInterface
{
    /**
     * Authorize user
     *
     * @param MvcEvent $e
     */
    public function authorize(MvcEvent $e);

    /**
     * Prepare navigation
     *
     * @param MvcEvent $e
     */
    public function prepareNavigation(MvcEvent $e);
}
