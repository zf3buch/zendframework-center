<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace ApplicationTest;

use Application\Module;
use PHPUnit_Framework_TestCase;
use Zend\ModuleManager\ModuleManagerInterface;

/**
 * Class ModuleTest
 *
 * @package ApplicationTest
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
    public function setUp()
    {
        $this->moduleRoot = realpath(__DIR__ . '/../');
    }

    /**
     * Test initialization
     */
    public function testInit()
    {
        $moduleManagerMock = $this->prophesize(
            ModuleManagerInterface::class
        );

        $this->assertTrue(class_exists(Module::class));

        $module = new Module();
        $module->init($moduleManagerMock->reveal());

        $this->assertTrue(defined('APPLICATION_MODULE_ROOT'));
        $this->assertEquals(
            $this->moduleRoot, realpath(APPLICATION_MODULE_ROOT)
        );
    }

    /**
     * Test get config
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
