<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace CompanyBackendTest\Controller;

use CompanyBackend\Controller\DisplayController;
use CompanyBackend\Controller\DisplayControllerFactory;
use CompanyModel\Repository\CompanyRepositoryInterface;
use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase;

/**
 * Class DisplayControllerFactoryTest
 *
 * @package CompanyBackendTest\Controller
 */
class DisplayControllerFactoryTest extends PHPUnit_Framework_TestCase
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
        /** @var CompanyRepositoryInterface $companyRepository */
        $companyRepository = $this->prophesize(
            CompanyRepositoryInterface::class
        );

        /** @var ContainerInterface $container */
        $container = $this->prophesize(ContainerInterface::class);
        $container->get(CompanyRepositoryInterface::class)
            ->willReturn($companyRepository)
            ->shouldBeCalled();

        $factory = new DisplayControllerFactory();

        $this->assertTrue(
            $factory instanceof DisplayControllerFactory
        );

        /** @var DisplayController $controller */
        $controller = $factory(
            $container->reveal(), DisplayController::class
        );

        $this->assertTrue($controller instanceof DisplayController);

        $this->assertAttributeEquals(
            $companyRepository->reveal(), 'companyRepository', $controller
        );
    }
}
