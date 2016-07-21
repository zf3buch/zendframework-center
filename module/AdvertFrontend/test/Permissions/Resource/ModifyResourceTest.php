<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertFrontendTest\Permissions\Resource;

use AdvertFrontend\Permissions\Resource\ModifyResource;
use PHPUnit_Framework_TestCase;

/**
 * Class ModifyResourceTest
 *
 * @package AdvertFrontendTest\Permissions\Resource
 */
class ModifyResourceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group advert-backend
     * @group permissions
     */
    public function testClassExists()
    {
        $this->assertTrue(class_exists(ModifyResource::class));
    }
}
