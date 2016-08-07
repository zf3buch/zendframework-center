<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertFrontendTest\Permissions\Resource;

use AdvertFrontend\Permissions\Resource\DisplayResource;
use PHPUnit_Framework_TestCase;

/**
 * Class DisplayResourceTest
 *
 * @package AdvertFrontendTest\Permissions\Resource
 */
class DisplayResourceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group advert-frontend
     * @group permissions
     */
    public function testClassExists()
    {
        $this->assertTrue(class_exists(DisplayResource::class));
    }
}
