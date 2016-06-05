<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace CompanyBackend\Form;

use CompanyModel\Config\CompanyConfigInterface;
use CompanyModel\Hydrator\CompanyHydrator;
use CompanyModel\InputFilter\CompanyInputFilter;
use Interop\Container\ContainerInterface;
use Zend\Hydrator\HydratorPluginManager;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterPluginManager;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class CompanyFormFactory
 *
 * @package CompanyBackend\Form
 */
class CompanyFormFactory implements FactoryInterface
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
        /** @var HydratorPluginManager $hydratorManager */
        $hydratorManager = $container->get('HydratorManager');

        /** @var InputFilterPluginManager $inputFilterManager */
        $inputFilterManager = $container->get('InputFilterManager');

        /** @var CompanyHydrator $companyHydrator */
        $companyHydrator = $hydratorManager->get(CompanyHydrator::class);

        /** @var InputFilterInterface $companyInputFilter */
        $companyInputFilter = $inputFilterManager->get(
            CompanyInputFilter::class
        );

        /** @var CompanyConfigInterface $companyConfig */
        $companyConfig = $container->get(CompanyConfigInterface::class);

        $form = new CompanyForm();
        $form->setHydrator($companyHydrator);
        $form->setInputFilter($companyInputFilter);
        $form->setStatusOptions($companyConfig->getStatusOptions());

        return $form;
    }
}
