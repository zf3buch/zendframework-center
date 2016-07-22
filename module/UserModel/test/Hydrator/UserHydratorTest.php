<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserModelTest\Hydrator;

use UserModel\Hydrator\UserHydrator;
use PHPUnit_Framework_TestCase;

/**
 * Class UserHydratorTest
 *
 * @package UserModelTest\Hydrator
 */
class UserHydratorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group user-model
     * @group model
     */
    public function testClassExists()
    {
        $this->assertTrue(class_exists(UserHydrator::class));
    }
}
