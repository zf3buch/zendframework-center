<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontend\Authentication;

use Interop\Container\ContainerInterface;
use UserFrontend\Form\UserLoginFormInterface;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Form\FormElementManager\FormElementManagerTrait;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AuthenticationListenerFactory
 *
 * @package UserFrontend\Authentication
 */
class AuthenticationListenerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null|null    $options
     *
     * @return mixed
     */
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ) {
        /** @var FormElementManagerTrait $formElementManager */
        $formElementManager = $container->get('FormElementManager');

        $authService = $container->get(
            AuthenticationServiceInterface::class
        );

        $userLoginForm = $formElementManager->get(
            UserLoginFormInterface::class
        );

        $authListener = new AuthenticationListener(
            $authService, $userLoginForm
        );

        return $authListener;
    }
}
