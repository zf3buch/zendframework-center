<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontend\Controller;

use Interop\Container\ContainerInterface;
use UserFrontend\Form\UserLoginForm;
use UserFrontend\Form\UserLoginFormInterface;
use UserModel\Repository\UserRepositoryInterface;
use Zend\Form\FormElementManager\FormElementManagerTrait;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class LoginControllerFactory
 *
 * @package UserFrontend\Controller
 */
class LoginControllerFactory implements FactoryInterface
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

        $userRepository = $container->get(
            UserRepositoryInterface::class
        );

        /** @var UserLoginFormInterface $userForm */
        $userForm = $formElementManager->get(UserLoginForm::class);

        $controller = new LoginController();
        $controller->setUserRepository($userRepository);
        $controller->setUserForm($userForm);

        return $controller;
    }
}
