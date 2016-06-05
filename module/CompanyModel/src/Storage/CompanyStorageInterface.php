<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace CompanyModel\Storage;

use CompanyModel\Entity\CompanyEntity;
use Zend\Paginator\Paginator;

/**
 * Interface CompanyStorageInterface
 *
 * @package CompanyModel\Storage
 */
interface CompanyStorageInterface
{
    /**
     * Fetch an company collection by type from storage
     *
     * @param int $page
     * @param int $count
     *
     * @return Paginator
     */
    public function fetchCompanyCollection($page = 1, $count = 5);

    /**
     * Fetch an company entity by id from storage
     *
     * @param $id
     *
     * @return CompanyEntity
     */
    public function fetchCompanyEntity($id);

    /**
     * Insert new company entity to storage
     *
     * @param CompanyEntity $company
     *
     * @return mixed
     */
    public function insertCompany(CompanyEntity $company);

    /**
     * Update existing company entity in storage
     *
     * @param CompanyEntity $company
     *
     * @return mixed
     */
    public function updateCompany(CompanyEntity $company);

    /**
     * Delete existing company entity from storage
     *
     * @param CompanyEntity $company
     *
     * @return mixed
     */
    public function deleteCompany(CompanyEntity $company);
}