<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserModelTest\Repository;

use UserModel\Repository\UserRepository;
use PHPUnit_Framework_TestCase;

/**
 * Class UserRepositoryTest
 *
 * @package UserModelTest\Repository
 */
class UserRepositoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group user-backend
     * @group model
     */
    public function testClassExists()
    {
        $this->assertTrue(class_exists(UserRepository::class));
    }
}
