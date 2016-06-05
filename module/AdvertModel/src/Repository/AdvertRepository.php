<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertModel\Repository;

use AdvertModel\Storage\AdvertStorageInterface;
use Zend\Paginator\Paginator;

/**
 * Class AdvertRepository
 *
 * @package AdvertModel\Repository
 */
class AdvertRepository implements AdvertRepositoryInterface
{
    /**
     * @var AdvertStorageInterface
     */
    private $advertStorage;

    /**
     * AdvertRepository constructor.
     *
     * @param AdvertStorageInterface $advertStorage
     */
    public function __construct(AdvertStorageInterface $advertStorage)
    {
        $this->advertStorage = $advertStorage;
    }

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
    ) {
        return $this->advertStorage->fetchAdvertCollection(
            $type, $approved, $page, $count
        );
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
        return $this->advertStorage->fetchAdvertEntity($id);
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
        return $this->advertStorage->fetchRandomAdvertEntity($type);
    }
}
