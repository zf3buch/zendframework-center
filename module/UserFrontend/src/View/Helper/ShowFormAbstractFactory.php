<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontend\View\Helper;

use Interop\Container\ContainerInterface;
use UserFrontend\Form\AbstractUserForm;
use UserFrontend\Form\UserEditFormInterface;
use UserFrontend\Form\UserFormInterface;
use UserFrontend\Form\UserLoginFormInterface;
use UserFrontend\Form\UserRegisterFormInterface;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Class ShowFormAbstractFactory
 *
 * @package UserFrontend\View\Helper
 */
class ShowFormAbstractFactory implements AbstractFactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     *
     * @return mixed
     */
    public function canCreate(ContainerInterface $container, $requestedName
    ) {
        if (!class_exists($requestedName)) {
            return false;
        }

        return ($requestedName instanceof AbstractShowForm);
    }

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
        $formName = $this->buildFormName($requestedName);

        /** @var FormElementManagerV3Polyfill $formElementManager */
        $formElementManager = $container->get('FormElementManager');

        /** @var AbstractUserForm $userForm */
        $userForm = $formElementManager->get($formName);

        /** @var AbstractShowForm $viewHelper */
        $viewHelper = new $requestedName();
        $viewHelper->setUserForm($userForm);

        return $viewHelper;
    }

    /**
     * @param $requestedName
     *
     * @return string
     */
    private function buildFormName($requestedName)
    {
        switch ($requestedName) {
            case 'UserFrontend\View\Helper\ShowRegisterForm':
                return UserRegisterFormInterface::class;

            case 'UserFrontend\View\Helper\ShowLoginForm':
                return UserLoginFormInterface::class;

            case 'UserFrontend\View\Helper\ShowEditForm':
                return UserEditFormInterface::class;
        }
    }
}
