<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertModelTest\InputFilter;

use AdvertModel\InputFilter\AdvertInputFilterFactory;
use PHPUnit_Framework_TestCase;

/**
 * Class AdvertInputFilterFactoryTest
 *
 * @package AdvertModelTest\InputFilter
 */
class AdvertInputFilterFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group advert-backend
     * @group model
     */
    public function testClassExists()
    {
        $this->assertTrue(class_exists(AdvertInputFilterFactory::class));
    }
}
