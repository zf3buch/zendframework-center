<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace CompanyModel\Repository;

use CompanyModel\Storage\CompanyStorageInterface;
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
     * @return array|bool
     */
    public function getSingleCompanyById($id)
    {
        return $this->companyStorage->fetchCompanyEntity($id);
    }
}
