<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertBackendTest\Form;

use AdvertBackend\Form\AdvertForm;
use PHPUnit_Framework_TestCase;

/**
 * Class AdvertFormTest
 *
 * @package AdvertBackendTest\Form
 */
class AdvertFormTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group advert-backend
     * @group form
     */
    public function testClassExists()
    {
        $this->assertTrue(class_exists(AdvertForm::class));
    }
}
