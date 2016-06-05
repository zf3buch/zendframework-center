<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertBackend\Controller;

use AdvertBackend\Form\AdvertForm;
use AdvertModel\Repository\AdvertRepositoryInterface;
use Interop\Container\ContainerInterface;
use Zend\Form\FormElementManager\FormElementManagerTrait;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ModifyControllerFactory
 *
 * @package AdvertBackend\Controller
 */
class ModifyControllerFactory implements FactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ) {
        /** @var FormElementManagerTrait $formElementManager */
        $formElementManager = $container->get('FormElementManager');

        $advertRepository = $container->get(
            AdvertRepositoryInterface::class
        );

        /** @var AdvertForm $advertForm */
        $advertForm = $formElementManager->get(AdvertForm::class);

        $controller = new ModifyController();
        $controller->setAdvertRepository($advertRepository);
        $controller->setAdvertForm($advertForm);

        return $controller;
    }
}
