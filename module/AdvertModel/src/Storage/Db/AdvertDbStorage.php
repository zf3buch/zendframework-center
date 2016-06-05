<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertModel\Storage\Db;

use AdvertModel\Entity\AdvertEntity;
use AdvertModel\Storage\AdvertStorageInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Hydrator\HydratorInterface;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

/**
 * Class AdvertDbStorage
 *
 * @package AdvertModel\Storage\Db
 */
class AdvertDbStorage implements AdvertStorageInterface
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
     * AdvertDbStorage constructor.
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
     * Fetch an advert collection by type from storage
     *
     * @param null $type
     * @param bool $approved
     * @param int  $page
     * @param int  $count
     *
     * @return Paginator
     */
    public function fetchAdvertCollection(
        $type = null, $approved = true, $page = 1, $count = 5
    ) {
        $select = $this->tableGateway->getSql()->select();
        $select->order(['advert.created' => 'DESC']);

        if ($approved) {
            $select->where->equalTo('advert.status', 'approved');
        }

        if (!is_null($type)) {
            $select->where->equalTo('advert.type', $type);
        }

        $select = $this->addCompanyJoinToSelect($select);

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
     * Fetch an advert entity by id from storage
     *
     * @param $id
     *
     * @return AdvertEntity
     */
    public function fetchAdvertEntity($id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where->equalTo('advert.id', $id);

        $select = $this->addCompanyJoinToSelect($select);

        /** @var ResultSet $resultSet */
        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet->current();
    }

    /**
     * Fetch a random advert entity by type from storage
     *
     * @param $type
     *
     * @return AdvertEntity
     */
    public function fetchRandomAdvertEntity($type)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where->equalTo('advert.type', $type);
        $select->where->equalTo('advert.status', 'approved');
        $select->order(new Expression('RAND()'));
        $select->limit(1);

        $select = $this->addCompanyJoinToSelect($select);

        /** @var ResultSet $resultSet */
        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet->current();
    }

    /**
     * Get next id for advert entity
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
     * Insert new advert entity to storage
     *
     * @param AdvertEntity $advert
     *
     * @return mixed
     */
    public function insertAdvert(AdvertEntity $advert)
    {
        $insertData = $this->hydrator->extract($advert);

        $insert = $this->tableGateway->getSql()->insert();
        $insert->values($insertData);

        return $this->tableGateway->insertWith($insert) > 0;
    }

    /**
     * Update existing advert entity in storage
     *
     * @param AdvertEntity $advert
     *
     * @return mixed
     */
    public function updateAdvert(AdvertEntity $advert)
    {
        $updateData = $this->hydrator->extract($advert);

        $update = $this->tableGateway->getSql()->update();
        $update->set($updateData);
        $update->where->equalTo('id', $advert->getId());

        return $this->tableGateway->updateWith($update) > 0;
    }

    /**
     * Delete existing advert entity from storage
     *
     * @param AdvertEntity $advert
     *
     * @return mixed
     */
    public function deleteAdvert(AdvertEntity $advert)
    {
        $delete = $this->tableGateway->getSql()->delete();
        $delete->where->equalTo('id', $advert->getId());

        return $this->tableGateway->deleteWith($delete) > 0;
    }

    /**
     * Add company join to select
     *
     * @param Select $select
     *
     * @return Select
     */
    private function addCompanyJoinToSelect(Select $select)
    {
        $select->join(
            'company',
            'advert.company = company.id',
            [
                'company_id'         => 'id',
                'company_registered' => 'registered',
                'company_updated'    => 'updated',
                'company_status'     => 'status',
                'company_name'       => 'name',
                'company_email'      => 'email',
                'company_contact'    => 'contact',
                'company_logo'       => 'logo',
            ]
        );

        return $select;
    }
}
