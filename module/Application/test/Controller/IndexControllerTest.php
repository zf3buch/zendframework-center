<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace ApplicationTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;

/**
 * Class IndexControllerTest
 *
 * @package ApplicationTest\Controller
 */
class IndexControllerTest extends AbstractControllerTestCase
{
    /**
     * Setup test cases
     */
    public function setUp()
    {
        $this->setApplicationConfig(
            include PROJECT_ROOT . '/config/application.config.php'
        );
        parent::setUp();
    }

    /**
     * Test index action
     */
    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('application');
        $this->assertControllerName('application_index');
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');
    }
}
