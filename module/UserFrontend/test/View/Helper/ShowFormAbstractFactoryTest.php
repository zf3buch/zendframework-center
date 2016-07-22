<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontendTest\View\Helper;

use UserFrontend\View\Helper\ShowFormAbstractFactory;
use PHPUnit_Framework_TestCase;

/**
 * Class ShowFormAbstractFactoryTest
 *
 * @package UserFrontendTest\View\Helper
 */
class ShowFormAbstractFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group user-backend
     * @group view-helper
     */
    public function testClassExists()
    {
        $this->assertTrue(class_exists(ShowFormAbstractFactory::class));
    }
}
