<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserModelTest\Entity;

use UserModel\Entity\UserEntity;
use PHPUnit_Framework_TestCase;

/**
 * Class UserEntityTest
 *
 * @package UserModelTest\Entity
 */
class UserEntityTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group user-backend
     * @group model
     */
    public function testClassExists()
    {
        $this->assertTrue(class_exists(UserEntity::class));
    }
}
