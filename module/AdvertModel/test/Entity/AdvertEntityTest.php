<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertModelTest\Entity;

use AdvertModel\Entity\AdvertEntity;
use PHPUnit_Framework_TestCase;

/**
 * Class AdvertEntityTest
 *
 * @package AdvertModelTest\Entity
 */
class AdvertEntityTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group advert-backend
     * @group model
     */
    public function testClassExists()
    {
        $this->assertTrue(class_exists(AdvertEntity::class));
    }
}
