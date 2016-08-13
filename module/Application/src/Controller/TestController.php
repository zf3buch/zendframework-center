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
            'company_id'         => '123',
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

        var_dump($advertEntity);
        var_dump($advertHydrator->extract($advertEntity));

        exit;
    }
}
