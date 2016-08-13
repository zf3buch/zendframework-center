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
use CompanyModel\Entity\CompanyEntity;
use DateTime;
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
     * Handle homepage
     */
    public function indexAction()
    {
        $companyEntity = new CompanyEntity();
        $companyEntity->setId('123');
        $companyEntity->setRegistered(new DateTime());
        $companyEntity->setUpdated(new DateTime());
        $companyEntity->setStatus('approved');
        $companyEntity->setName(' Name ');
        $companyEntity->setEmail('Email');
        $companyEntity->setContact('Contact');
        $companyEntity->setLogo('Logo');

        $advertEntity = new AdvertEntity();
        $advertEntity->setId('123');
        $advertEntity->setCreated(new DateTime());
        $advertEntity->setUpdated(new DateTime());
        $advertEntity->setStatus('approved');
        $advertEntity->setType('job');
        $advertEntity->setCompany($companyEntity);
        $advertEntity->setTitle('Title');
        $advertEntity->setText('Text');
        $advertEntity->setLocation('Location');

        var_dump($companyEntity);
        var_dump($advertEntity);
        exit;
    }
}
