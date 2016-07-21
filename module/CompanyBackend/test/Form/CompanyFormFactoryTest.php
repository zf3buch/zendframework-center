<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace CompanyBackendTest\Form;

use CompanyBackend\Form\CompanyFormFactory;
use PHPUnit_Framework_TestCase;

/**
 * Class CompanyFormFactoryTest
 *
 * @package CompanyBackendTest\Form
 */
class CompanyFormFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group company-backend
     * @group form
     */
    public function testClassExists()
    {
        $this->assertTrue(class_exists(CompanyFormFactory::class));
    }
}
