<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserModelTest\Repository;

use UserModel\Repository\UserRepositoryFactory;
use PHPUnit_Framework_TestCase;

/**
 * Class UserRepositoryFactoryTest
 *
 * @package UserModelTest\Repository
 */
class UserRepositoryFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group user-backend
     * @group model
     */
    public function testClassExists()
    {
        $this->assertTrue(class_exists(UserRepositoryFactory::class));
    }
}
