<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserBackendTest;

use UserBackend\Module;
use PHPUnit_Framework_TestCase;
use Zend\ModuleManager\ModuleManagerInterface;

/**
 * Class ModuleTest
 *
 * @package UserBackendTest
 */
class ModuleTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $moduleRoot = null;

    /**
     * Setup test cases
     */
    protected function setUp()
    {
        $this->moduleRoot = realpath(__DIR__ . '/../');
    }

    /**
     * Test initialization
     *
     * @group module
     * @group user-backend
     */
    public function testInit()
    {
        $moduleManagerMock = $this->prophesize(
            ModuleManagerInterface::class
        );

        $this->assertTrue(class_exists(Module::class));

        $module = new Module();
        $module->init($moduleManagerMock->reveal());

        $this->assertTrue(defined('USER_BACKEND_MODULE_ROOT'));
        $this->assertEquals(
            $this->moduleRoot, realpath(USER_BACKEND_MODULE_ROOT)
        );
    }

    /**
     * Test get config
     *
     * @group module
     * @group user-backend
     */
    public function testGetConfig()
    {
        $expectedConfig = include $this->moduleRoot
            . '/config/module.config.php';

        $module     = new Module();
        $configData = $module->getConfig();

        $this->assertEquals($expectedConfig, $configData);
    }
}
