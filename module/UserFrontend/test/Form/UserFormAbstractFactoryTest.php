<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontendTest\Form;

use UserFrontend\Form\UserFormAbstractFactory;
use PHPUnit_Framework_TestCase;

/**
 * Class UserFormAbstractFactoryTest
 *
 * @package UserFrontendTest\Form
 */
class UserFormAbstractFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group user-backend
     * @group form
     */
    public function testClassExists()
    {
        $this->assertTrue(class_exists(UserFormAbstractFactory::class));
    }
}
