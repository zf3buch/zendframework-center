<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserModel\Repository;

use UserModel\Entity\UserEntity;
use Zend\Paginator\Paginator;

/**
 * Interface UserRepositoryInterface
 *
 * @package UserModel\Repository
 */
interface UserRepositoryInterface
{
    /**
     * Get all users for a given page
     *
     * @param int $page
     * @param int $count
     *
     * @return Paginator
     */
    public function getUsersByPage($page = 1, $count = 5);

    /**
     * Get a single user by id
     *
     * @param $id
     *
     * @return UserEntity|bool
     */
    public function getSingleUserById($id);

    /**
     * Get user options
     *
     * @return array
     */
    public function getUserOptions();

    /**
     * Create a new user based on array data
     *
     * @param array $data
     *
     * @return UserEntity
     */
    public function createUserFromData(array $data = []);

    /**
     * Save user
     *
     * @param UserEntity $user
     *
     * @return boolean
     */
    public function saveUser(UserEntity $user);

    /**
     * Delete an user
     *
     * @param UserEntity $user
     *
     * @return boolean
     */
    public function deleteUser(UserEntity $user);
}
