<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertModel\Repository;

use AdvertModel\Entity\AdvertEntity;
use Zend\Paginator\Paginator;

/**
 * Interface AdvertRepositoryInterface
 *
 * @package AdvertModel\Repository
 */
interface AdvertRepositoryInterface
{
    /**
     * Get all adverts for a given page
     *
     * @param string|null $type
     * @param bool        $approved
     * @param int         $page
     * @param int         $count
     *
     * @return Paginator
     */
    public function getAdvertsByPage(
        $type = null, $approved = true, $page = 1, $count = 5
    );

    /**
     * Get a single advert by id
     *
     * @param $id
     *
     * @return AdvertEntity|bool
     */
    public function getSingleAdvertById($id);

    /**
     * Get a random job advert
     *
     * @param string $type
     *
     * @return AdvertEntity|bool
     */
    public function getRandomAdvert($type = 'job');

    /**
     * Create a new advert based on array data
     *
     * @param array $data
     *
     * @return AdvertEntity
     */
    public function createAdvertFromData(array $data = []);

    /**
     * Save advert
     *
     * @param AdvertEntity $advert
     *
     * @return boolean
     */
    public function saveAdvert(AdvertEntity $advert);

    /**
     * Delete an advert
     *
     * @param AdvertEntity $advert
     *
     * @return boolean
     */
    public function deleteAdvert(AdvertEntity $advert);
}
