<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace CompanyBackend\Controller;

use CompanyBackend\Form\CompanyForm;
use CompanyBackend\Form\CompanyFormInterface;
use CompanyModel\Repository\CompanyRepositoryInterface;
use Interop\Container\ContainerInterface;
use Zend\Form\FormElementManager\FormElementManagerTrait;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ModifyControllerFactory
 *
 * @package CompanyBackend\Controller
 */
class ModifyControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null|null    $options
     *
     * @return mixed
     */
    public function __invoke(
        ContainerInterface $container, $requestedName,
        array $options = null
    ) {
        /** @var FormElementManagerTrait $formElementManager */
        $formElementManager = $container->get('FormElementManager');

        $companyRepository = $container->get(
            CompanyRepositoryInterface::class
        );

        /** @var CompanyFormInterface $companyForm */
        $companyForm = $formElementManager->get(CompanyForm::class);

        $controller = new ModifyController();
        $controller->setCompanyRepository($companyRepository);
        $controller->setCompanyForm($companyForm);

        return $controller;
    }
}
