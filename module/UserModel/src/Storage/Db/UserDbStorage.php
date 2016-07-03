<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserModel\Storage\Db;

use UserModel\Entity\UserEntity;
use UserModel\Storage\UserStorageInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Hydrator\HydratorInterface;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

/**
 * Class UserDbStorage
 *
 * @package UserModel\Storage\Db
 */
class UserDbStorage implements UserStorageInterface
{
    /**
     * @var TableGatewayInterface|TableGateway
     */
    private $tableGateway;

    /**
     * @var HydratorInterface
     */
    private $hydrator;

    /**
     * UserDbStorage constructor.
     *
     * @param TableGatewayInterface $tableGateway
     */
    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;

        /** @var HydratingResultSet $resultSetPrototype */
        $resultSetPrototype = $this->tableGateway->getResultSetPrototype();

        $this->hydrator = $resultSetPrototype->getHydrator();
    }

    /**
     * Fetch an user collection by type from storage
     *
     * @param int $page
     * @param int $count
     *
     * @return Paginator
     */
    public function fetchUserCollection($page = 1, $count = 5)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->order(['email' => 'ASC']);

        $dbSelectAdapter = new DbSelect(
            $select,
            $this->tableGateway->getAdapter(),
            $this->tableGateway->getResultSetPrototype()
        );

        $paginator = new Paginator($dbSelectAdapter);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($count);

        return $paginator;
    }

    /**
     * Fetch an user entity by id from storage
     *
     * @param $id
     *
     * @return UserEntity
     */
    public function fetchUserEntity($id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where->equalTo('id', $id);

        /** @var ResultSet $resultSet */
        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet->current();
    }

    /**
     * Fetch all companies for an option list
     *
     * @return mixed
     */
    public function fetchUserOptions()
    {
        $select = $this->tableGateway->getSql()->select();

        /** @var ResultSet $resultSet */
        $resultSet = $this->tableGateway->selectWith($select);

        $options = [];

        /** @var UserEntity $user */
        foreach ($resultSet as $user) {
            $options[$user->getId()] = $user->getEmail();
        }

        return $options;
    }

    /**
     * Get next id for user entity
     *
     * @return integer
     */
    public function nextId()
    {
        $insert = $this->tableGateway->getSql()->insert();
        $insert->values(['id' => null]);

        $this->tableGateway->insertWith($insert);

        return $this->tableGateway->getLastInsertValue();
    }

    /**
     * Insert new user entity to storage
     *
     * @param UserEntity $user
     *
     * @return mixed
     */
    public function insertUser(UserEntity $user)
    {
        $insertData = $this->hydrator->extract($user);

        $insert = $this->tableGateway->getSql()->insert();
        $insert->values($insertData);

        return $this->tableGateway->insertWith($insert) > 0;
    }

    /**
     * Update existing user entity in storage
     *
     * @param UserEntity $user
     *
     * @return mixed
     */
    public function updateUser(UserEntity $user)
    {
        $updateData = $this->hydrator->extract($user);

        if (empty($updateData['password'])) {
            unset($updateData['password']);
        }

        $update = $this->tableGateway->getSql()->update();
        $update->set($updateData);
        $update->where->equalTo('id', $user->getId());

        return $this->tableGateway->updateWith($update) > 0;
    }

    /**
     * Delete existing user entity from storage
     *
     * @param UserEntity $user
     *
     * @return mixed
     */
    public function deleteUser(UserEntity $user)
    {
        $delete = $this->tableGateway->getSql()->delete();
        $delete->where->equalTo('id', $user->getId());

        return $this->tableGateway->deleteWith($delete) > 0;
    }
}
