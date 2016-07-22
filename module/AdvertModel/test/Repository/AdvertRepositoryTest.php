<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertModelTest\Repository;

use AdvertModel\Repository\AdvertRepository;
use PHPUnit_Framework_TestCase;

/**
 * Class AdvertRepositoryTest
 *
 * @package AdvertModelTest\Repository
 */
class AdvertRepositoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group advert-backend
     * @group model
     */
    public function testClassExists()
    {
        $this->assertTrue(class_exists(AdvertRepository::class));
    }
}
