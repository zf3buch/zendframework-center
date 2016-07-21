<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontendTest\Permissions\Resource;

use UserFrontend\Permissions\Resource\IndexResource;
use PHPUnit_Framework_TestCase;

/**
 * Class IndexResourceTest
 *
 * @package UserFrontendTest\Permissions\Resource
 */
class IndexResourceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group advert-backend
     * @group permissions
     */
    public function testClassExists()
    {
        $this->assertTrue(class_exists(IndexResource::class));
    }
}
