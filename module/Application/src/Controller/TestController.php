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
use CompanyModel\Entity\CompanyEntity;
use CompanyModel\Hydrator\CompanyHydrator;
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
        $companyData = [
            'id'         => '123',
            'registered' => new DateTime(),
            'updated'    => new DateTime(),
            'status'     => 'approved',
            'name'       => ' Name ',
            'email'      => 'Email',
            'contact'    => 'Contact',
        ];

        $companyEntity = new CompanyEntity();

        $companyHydrator = new CompanyHydrator();
        $companyHydrator->hydrate($companyData, $companyEntity);

        $advertData = [
            'id'       => '123',
            'created'  => new DateTime(),
            'updated'  => new DateTime(),
            'status'   => 'approved',
            'type'     => 'job',
            'company'  => $companyEntity,
            'title'    => ' Title ',
            'text'     => 'Text',
            'location' => 'Location',
        ];

        $advertEntity = new AdvertEntity();

        $advertHydrator = new AdvertHydrator();
        $advertHydrator->hydrate($advertData, $advertEntity);

        var_dump($companyEntity);
        var_dump($advertEntity);

        var_dump($companyHydrator->extract($companyEntity));
        var_dump($advertHydrator->extract($advertEntity));

        exit;
    }
}
