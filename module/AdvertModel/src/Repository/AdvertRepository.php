<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertModel\Repository;

/**
 * Class AdvertRepository
 *
 * @package AdvertModel\Repository
 */
class AdvertRepository implements AdvertRepositoryInterface
{
    /**
     * @var array
     */
    private $advertData = [];

    /**
     * @var array
     */
    private $companyData = [];

    /**
     * AdvertRepository constructor.
     *
     * @param array $advertData
     * @param array $companyData
     */
    public function __construct(array $advertData, array $companyData)
    {
        $this->advertData  = $advertData;
        $this->companyData = $companyData;
    }

    /**
     * Get all adverts for a given page
     *
     * @param string|null $type
     * @param bool        $approved
     * @param int         $page
     * @param int         $count
     *
     * @return mixed
     */
    public function getAdvertsByPage(
        $type = null, $approved = true, $page = 1, $count = 5
    ) {
        if (!is_null($type)) {
            $jobAdverts = $this->getAdvertsByType($type, $approved);
        } else {
            $jobAdverts = $this->advertData;
        }

        $offset = ($page - 1) * $count;

        $advertList = array_slice($jobAdverts, $offset, $count, true);

        foreach ($advertList as $key => $advert) {
            $company = $this->companyData[$advert['company']];

            $advertList[$key]['company'] = $company;
        }

        return $advertList;
    }

    /**
     * Get a single advert by id
     *
     * @param $id
     *
     * @return array|bool
     */
    public function getSingleAdvertById($id)
    {
        if (!isset($this->advertData[$id])) {
            return false;
        }

        $advert  = $this->advertData[$id];
        $company = $this->companyData[$advert['company']];

        $advert['company'] = $company;

        return $advert;
    }

    /**
     * Get a random job advert
     *
     * @param string $type
     *
     * @return array|bool
     */
    public function getRandomAdvert($type = 'job')
    {
        $jobAdverts = $this->getAdvertsByType($type, true);

        if (empty($jobAdverts)) {
            return false;
        }

        $advertKeys = array_keys($jobAdverts);

        $randomKey = array_rand($advertKeys);

        return $this->getSingleAdvertById($advertKeys[$randomKey]);
    }

    /**
     * Get adverts by type
     *
     * @param string $type
     * @param bool   $approved
     *
     * @return array
     */
    private function getAdvertsByType($type, $approved = true)
    {
        $jobAdverts = [];

        foreach ($this->advertData as $advert) {
            if ($approved && $advert['status'] != 'approved') {
                continue;
            }

            if ($advert['type'] != $type) {
                continue;
            }

            $jobAdverts[$advert['id']] = $advert;
        }

        return $jobAdverts;
    }
}
