<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertModelTest\Storage\Db;

use AdvertModel\Storage\Db\AdvertDbStorageFactory;
use PHPUnit_Framework_TestCase;

/**
 * Class AdvertDbStorageFactoryTest
 *
 * @package AdvertModelTest\Storage\Db
 */
class AdvertDbStorageFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group advert-model
     * @group model
     */
    public function testClassExists()
    {
        $this->assertTrue(class_exists(AdvertDbStorageFactory::class));
    }
}
