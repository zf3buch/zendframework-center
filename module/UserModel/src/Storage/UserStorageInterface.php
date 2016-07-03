<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserModel\Storage;

use UserModel\Entity\UserEntity;
use Zend\Paginator\Paginator;

/**
 * Interface UserStorageInterface
 *
 * @package UserModel\Storage
 */
interface UserStorageInterface
{
    /**
     * Fetch an user collection by type from storage
     *
     * @param int $page
     * @param int $count
     *
     * @return Paginator
     */
    public function fetchUserCollection($page = 1, $count = 5);

    /**
     * Fetch an user entity by id from storage
     *
     * @param $id
     *
     * @return UserEntity
     */
    public function fetchUserEntity($id);

    /**
     * Fetch all companies for an option list
     *
     * @return mixed
     */
    public function fetchUserOptions();

    /**
     * Get next id for user entity
     *
     * @return integer
     */
    public function nextId();

    /**
     * Insert new user entity to storage
     *
     * @param UserEntity $user
     *
     * @return mixed
     */
    public function insertUser(UserEntity $user);

    /**
     * Update existing user entity in storage
     *
     * @param UserEntity $user
     *
     * @return mixed
     */
    public function updateUser(UserEntity $user);

    /**
     * Delete existing user entity from storage
     *
     * @param UserEntity $user
     *
     * @return mixed
     */
    public function deleteUser(UserEntity $user);
}