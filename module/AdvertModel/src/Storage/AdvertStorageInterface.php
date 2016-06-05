<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertModel\Storage;

use AdvertModel\Entity\AdvertEntity;
use Zend\Paginator\Paginator;

/**
 * Interface AdvertStorageInterface
 *
 * @package AdvertModel\Storage
 */
interface AdvertStorageInterface
{
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
    );

    /**
     * Fetch an advert entity by id from storage
     *
     * @param $id
     *
     * @return AdvertEntity
     */
    public function fetchAdvertEntity($id);

    /**
     * Fetch a random advert entity by type from storage
     *
     * @param $type
     *
     * @return AdvertEntity
     */
    public function fetchRandomAdvertEntity($type);

    /**
     * Get next id for advert entity
     *
     * @return integer
     */
    public function nextId();

    /**
     * Insert new advert entity to storage
     *
     * @param AdvertEntity $advert
     *
     * @return mixed
     */
    public function insertAdvert(AdvertEntity $advert);

    /**
     * Update existing advert entity in storage
     *
     * @param AdvertEntity $advert
     *
     * @return mixed
     */
    public function updateAdvert(AdvertEntity $advert);

    /**
     * Delete existing advert entity from storage
     *
     * @param AdvertEntity $advert
     *
     * @return mixed
     */
    public function deleteAdvert(AdvertEntity $advert);
}
