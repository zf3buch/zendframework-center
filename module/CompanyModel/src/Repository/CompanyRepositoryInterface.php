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
use Zend\Paginator\Paginator;

/**
 * Interface CompanyRepositoryInterface
 *
 * @package CompanyModel\Repository
 */
interface CompanyRepositoryInterface
{
    /**
     * Get all companies for a given page
     *
     * @param int $page
     * @param int $count
     *
     * @return Paginator
     */
    public function getCompaniesByPage($page = 1, $count = 5);

    /**
     * Get a single company by id
     *
     * @param $id
     *
     * @return CompanyEntity|bool
     */
    public function getSingleCompanyById($id);

    /**
     * Get company options
     *
     * @return array
     */
    public function getCompanyOptions();

    /**
     * Create a new company based on array data
     *
     * @param array $data
     *
     * @return CompanyEntity
     */
    public function createCompanyFromData(array $data = []);

    /**
     * Save company
     *
     * @param CompanyEntity $company
     *
     * @return boolean
     */
    public function saveCompany(CompanyEntity $company);

    /**
     * Delete an company
     *
     * @param CompanyEntity $company
     *
     * @return boolean
     */
    public function deleteCompany(CompanyEntity $company);
}
