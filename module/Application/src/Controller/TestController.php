<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Application\Controller;

use AdvertModel\Entity\AdvertEntity;
use AdvertModel\Hydrator\AdvertHydrator;
use AdvertModel\Storage\Db\AdvertDbStorage;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Test controller
 *
 * Handles the homepage and other pages
 *
 * @package    Application
 */
class TestController extends AbstractActionController
{
    /**
     * @var AdvertDbStorage
     */
    private $advertDbStorage;

    /**
     * @param AdvertDbStorage $advertDbStorage
     */
    public function setAdvertDbStorage($advertDbStorage)
    {
        $this->advertDbStorage = $advertDbStorage;
    }

    /**
     * Handle homepage
     */
    public function indexAction()
    {
        $advertEntity = $this->advertDbStorage->fetchAdvertEntity(1);

        var_dump($advertEntity);

        $advertCollection = $this->advertDbStorage->fetchAdvertCollection(
            'job', 1, 5
        );

        var_dump($advertCollection->getCurrentItems());

        $advertData = [
            'id'                 => '123',
            'created'            => date('Y-m-d H:i:s'),
            'updated'            => date('Y-m-d H:i:s'),
            'status'             => 'approved',
            'type'               => 'job',
            'company'            => '123',
            'title'              => ' Title ',
            'text'               => 'Text',
            'location'           => 'Location',
            'company_id'         => '3',
            'company_registered' => date('Y-m-d H:i:s'),
            'company_updated'    => date('Y-m-d H:i:s'),
            'company_status'     => 'approved',
            'company_name'       => ' Name ',
            'company_email'      => 'Email',
            'company_contact'    => 'Contact',
            'company_logo'       => 'Logo',
        ];

        $advertEntity = new AdvertEntity();

        $advertHydrator = new AdvertHydrator();
        $advertHydrator->hydrate($advertData, $advertEntity);

        $result = $this->advertDbStorage->insertAdvert($advertEntity);

        var_dump($result);

        $advertEntity = $this->advertDbStorage->fetchAdvertEntity(123);

        var_dump($advertEntity);

        $advertEntity->setLocation('LOCATION');

        $result = $this->advertDbStorage->updateAdvert($advertEntity);

        var_dump($result);

        $advertEntity = $this->advertDbStorage->fetchAdvertEntity(123);

        var_dump($advertEntity);

        $result = $this->advertDbStorage->deleteAdvert($advertEntity);

        var_dump($result);

        $advertEntity = $this->advertDbStorage->fetchAdvertEntity(123);

        var_dump($advertEntity);

        exit;
    }
}
