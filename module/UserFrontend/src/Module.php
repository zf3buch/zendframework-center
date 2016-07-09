<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontend;

use UserFrontend\Authentication\AuthenticationListenerInterface;
use UserFrontend\Authorization\AuthorizationListenerInterface;
use Zend\Config\Factory;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\MvcEvent;

/**
 * Class Module
 *
 * @package UserFrontend
 */
class Module implements ConfigProviderInterface, InitProviderInterface
{
    /**
     * Initialize module
     *
     * @param ModuleManagerInterface $manager
     */
    public function init(ModuleManagerInterface $manager)
    {
        define('USER_FRONTEND_MODULE_ROOT', __DIR__ . '/../');
    }

    /**
     * @param EventInterface|MvcEvent $e
     */
    public function onBootstrap(EventInterface $e)
    {
        // get manager
        $serviceManager = $e->getApplication()->getServiceManager();
        $eventManager   = $e->getApplication()->getEventManager();

        /** @var AuthenticationListenerInterface $authenticationListener */
        $authenticationListener = $serviceManager->get(
            AuthenticationListenerInterface::class
        );
        $authenticationListener->attach($eventManager);

        /** @var AuthorizationListenerInterface $authorizationListener */
        $authorizationListener = $serviceManager->get(
            AuthorizationListenerInterface::class
        );
        $authorizationListener->attach($eventManager);
    }

    /**
     * Get module configuration
     */
    public function getConfig()
    {
        return Factory::fromFile(
            USER_FRONTEND_MODULE_ROOT . '/config/module.config.php'
        );
    }
}
