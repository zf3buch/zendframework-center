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
use UserFrontend\Form\UserEditForm;
use UserFrontend\Form\UserEditFormInterface;
use Zend\Form\FormElementManager\FormElementManagerTrait;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class IndexControllerFactory
 *
 * @package UserFrontend\Controller
 */
class IndexControllerFactory implements FactoryInterface
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

        /** @var UserEditFormInterface $userForm */
        $userForm = $formElementManager->get(UserEditForm::class);

        $controller = new IndexController();
        $controller->setUserForm($userForm);

        return $controller;
    }
}
