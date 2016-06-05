<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace CompanyModel\Storage\Db;

use CompanyModel\Entity\CompanyEntity;
use CompanyModel\Storage\CompanyStorageInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Hydrator\HydratorInterface;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

/**
 * Class CompanyDbStorage
 *
 * @package CompanyModel\Storage\Db
 */
class CompanyDbStorage implements CompanyStorageInterface
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
     * CompanyDbStorage constructor.
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
     * Fetch an company collection by type from storage
     *
     * @param int $page
     * @param int $count
     *
     * @return Paginator
     */
    public function fetchCompanyCollection($page = 1, $count = 5)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->order(['name' => 'ASC']);

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
     * Fetch an company entity by id from storage
     *
     * @param $id
     *
     * @return CompanyEntity
     */
    public function fetchCompanyEntity($id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where->equalTo('id', $id);

        /** @var ResultSet $resultSet */
        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet->current();
    }

    /**
     * Insert new company entity to storage
     *
     * @param CompanyEntity $company
     *
     * @return mixed
     */
    public function insertCompany(CompanyEntity $company)
    {
        $insertData = $this->hydrator->extract($company);

        $insert = $this->tableGateway->getSql()->insert();
        $insert->values($insertData);

        return $this->tableGateway->insertWith($insert) > 0;
    }

    /**
     * Update existing company entity in storage
     *
     * @param CompanyEntity $company
     *
     * @return mixed
     */
    public function updateCompany(CompanyEntity $company)
    {
        $updateData = $this->hydrator->extract($company);

        $update = $this->tableGateway->getSql()->update();
        $update->set($updateData);
        $update->where->equalTo('id', $company->getId());

        return $this->tableGateway->updateWith($update) > 0;
    }

    /**
     * Delete existing company entity from storage
     *
     * @param CompanyEntity $company
     *
     * @return mixed
     */
    public function deleteCompany(CompanyEntity $company)
    {
        $delete = $this->tableGateway->getSql()->delete();
        $delete->where->equalTo('id', $company->getId());

        return $this->tableGateway->deleteWith($delete) > 0;
    }
}
