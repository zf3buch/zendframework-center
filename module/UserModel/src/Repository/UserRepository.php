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
use UserModel\Storage\UserStorageInterface;
use DateTime;
use Zend\Paginator\Paginator;

/**
 * Class UserRepository
 *
 * @package UserModel\Repository
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * @var UserStorageInterface
     */
    private $userStorage;

    /**
     * UserRepository constructor.
     *
     * @param UserStorageInterface $userStorage
     */
    public function __construct(UserStorageInterface $userStorage)
    {
        $this->userStorage = $userStorage;
    }

    /**
     * Get all users for a given page
     *
     * @param int $page
     * @param int $count
     *
     * @return Paginator
     */
    public function getUsersByPage($page = 1, $count = 5)
    {
        return $this->userStorage->fetchUserCollection(
            $page, $count
        );
    }

    /**
     * Get a single user by id
     *
     * @param $id
     *
     * @return UserEntity|bool
     */
    public function getSingleUserById($id)
    {
        return $this->userStorage->fetchUserEntity($id);
    }

    /**
     * Get user options
     *
     * @return array
     */
    public function getUserOptions()
    {
        return $this->userStorage->fetchUserOptions();
    }

    /**
     * Create a new user based on array data
     *
     * @param array $data
     *
     * @return UserEntity
     */
    public function createUserFromData(array $data = [])
    {
        $nextId = $this->userStorage->nextId();

        $user = new UserEntity();
        $user->setId($nextId);
        $user->setRegistered(new DateTime());
        $user->setUpdated(new DateTime());
        $user->setStatus($data['status']);
        $user->setRole($data['role']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);

        return $user;
    }

    /**
     * Save user
     *
     * @param UserEntity $user
     *
     * @return boolean
     */
    public function saveUser(UserEntity $user)
    {
        if (!$user->getId()) {
            return $this->userStorage->insertUser($user);
        } else {
            return $this->userStorage->updateUser($user);
        }
    }

    /**
     * Delete an user
     *
     * @param UserEntity $user
     *
     * @return boolean
     */
    public function deleteUser(UserEntity $user)
    {
        return $this->userStorage->deleteUser($user);
    }
}
