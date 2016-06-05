<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace CompanyModel\Repository;

use CompanyModel\Entity\CompanyEntity;
use CompanyModel\Storage\CompanyStorageInterface;
use DateTime;
use Zend\Paginator\Paginator;

/**
 * Class CompanyRepository
 *
 * @package CompanyModel\Repository
 */
class CompanyRepository implements CompanyRepositoryInterface
{
    /**
     * @var CompanyStorageInterface
     */
    private $companyStorage;

    /**
     * CompanyRepository constructor.
     *
     * @param CompanyStorageInterface $companyStorage
     */
    public function __construct(CompanyStorageInterface $companyStorage)
    {
        $this->companyStorage = $companyStorage;
    }

    /**
     * Get all companies for a given page
     *
     * @param int $page
     * @param int $count
     *
     * @return Paginator
     */
    public function getCompaniesByPage($page = 1, $count = 5)
    {
        return $this->companyStorage->fetchCompanyCollection(
            $page, $count
        );
    }

    /**
     * Get a single company by id
     *
     * @param $id
     *
     * @return CompanyEntity|bool
     */
    public function getSingleCompanyById($id)
    {
        return $this->companyStorage->fetchCompanyEntity($id);
    }

    /**
     * Get company options
     *
     * @return array
     */
    public function getCompanyOptions()
    {
        return $this->companyStorage->fetchCompanyOptions();
    }

    /**
     * Create a new company based on array data
     *
     * @param array $data
     *
     * @return CompanyEntity
     */
    public function createCompanyFromData(array $data = [])
    {
        $nextId = $this->companyStorage->nextId();

        $company = new CompanyEntity();
        $company->setId($nextId);
        $company->setRegistered(new DateTime());
        $company->setUpdated(new DateTime());
        $company->setStatus($data['status']);
        $company->setName($data['name']);
        $company->setEmail($data['email']);
        $company->setContact($data['contact']);

        return $company;
    }

    /**
     * Save company
     *
     * @param CompanyEntity $company
     *
     * @return boolean
     */
    public function saveCompany(CompanyEntity $company)
    {
        if (!$company->getId()) {
            return $this->companyStorage->insertCompany($company);
        } else {
            return $this->companyStorage->updateCompany($company);
        }
    }

    /**
     * Delete an company
     *
     * @param CompanyEntity $company
     *
     * @return boolean
     */
    public function deleteCompany(CompanyEntity $company)
    {
        return $this->companyStorage->deleteCompany($company);
    }
}
