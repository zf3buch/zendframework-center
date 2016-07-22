<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontendTest\Form;

use UserFrontend\Form\UserLoginForm;
use PHPUnit_Framework_TestCase;

/**
 * Class UserLoginFormTest
 *
 * @package UserFrontendTest\Form
 */
class UserLoginFormTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group user-backend
     * @group form
     */
    public function testClassExists()
    {
        $this->assertTrue(class_exists(UserLoginForm::class));
    }
}
