<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace CompanyBackendTest\Form;

use CompanyBackend\Form\CompanyForm;
use PHPUnit_Framework_TestCase;

/**
 * Class CompanyFormTest
 *
 * @package CompanyBackendTest\Form
 */
class CompanyFormTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group company-backend
     * @group form
     */
    public function testClassExists()
    {
        $this->assertTrue(class_exists(CompanyForm::class));
    }
}
