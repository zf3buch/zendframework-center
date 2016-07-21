<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace CompanyBackendTest\Controller;

use CompanyBackend\Controller\ModifyController;
use CompanyBackend\Controller\ModifyControllerFactory;
use CompanyBackend\Form\CompanyForm;
use CompanyModel\Repository\CompanyRepositoryInterface;
use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase;

/**
 * Class ModifyControllerFactoryTest
 *
 * @package CompanyBackendTest\Controller
 */
class ModifyControllerFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test factory
     *
     * @group controller
     * @group factory
     * @group company-backend
     */
    public function testFactory()
    {
        /** @var ContainerInterface $formElementManager */
        $formElementManager = $this->prophesize(ContainerInterface::class);

        /** @var CompanyForm $companyForm */
        $companyForm = $this->prophesize(CompanyForm::class);

        $formElementManager->get(CompanyForm::class)
            ->willReturn($companyForm)
            ->shouldBeCalled();

        /** @var ContainerInterface $container */
        $container = $this->prophesize(ContainerInterface::class);

        /** @var CompanyRepositoryInterface $companyRepository */
        $companyRepository = $this->prophesize(
            CompanyRepositoryInterface::class
        );

        $container->get(CompanyRepositoryInterface::class)
            ->willReturn($companyRepository)
            ->shouldBeCalled();

        $container->get('FormElementManager')
            ->willReturn($formElementManager)
            ->shouldBeCalled();

        $factory = new ModifyControllerFactory();

        $this->assertTrue(
            $factory instanceof ModifyControllerFactory
        );

        /** @var ModifyController $controller */
        $controller = $factory(
            $container->reveal(), ModifyController::class
        );

        $this->assertTrue($controller instanceof ModifyController);

        $this->assertAttributeEquals(
            $companyRepository->reveal(), 'companyRepository', $controller
        );

        $this->assertAttributeEquals(
            $companyForm->reveal(), 'companyForm', $controller
        );
    }
}
