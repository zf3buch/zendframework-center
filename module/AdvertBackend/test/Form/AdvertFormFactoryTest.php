<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertBackendTest\Form;

use AdvertBackend\Form\AdvertFormFactory;
use PHPUnit_Framework_TestCase;

/**
 * Class AdvertFormFactoryTest
 *
 * @package AdvertBackendTest\Form
 */
class AdvertFormFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group advert-backend
     * @group form
     */
    public function testClassExists()
    {
        $this->assertTrue(class_exists(AdvertFormFactory::class));
    }
}
