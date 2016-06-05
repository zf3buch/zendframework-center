<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertBackend\Controller;

use AdvertModel\Repository\AdvertRepositoryInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class DisplayController
 *
 * @package AdvertBackend\Controller
 */
class DisplayController extends AbstractActionController
{
    /**
     * @var AdvertRepositoryInterface
     */
    private $advertRepository;

    /**
     * @param AdvertRepositoryInterface $advertRepository
     */
    public function setAdvertRepository($advertRepository)
    {
        $this->advertRepository = $advertRepository;
    }

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        $page = $this->params()->fromRoute('page', 1);

        $advertList = $this->advertRepository->getAdvertsByPage(
            null, false, $page, 15
        );

        if (!$advertList) {
            return $this->redirect()->toRoute('advert-backend', [], true);
        }

        var_dump($advertList);
        exit;
    }

    /**
     * @return ViewModel
     */
    public function showAction()
    {
        $id = $this->params()->fromRoute('id');

        if (!$id) {
            return $this->redirect()->toRoute('advert-backend', [], true);
        }

        $advert = $this->advertRepository->getSingleAdvertById($id);

        if (!$advert) {
            return $this->redirect()->toRoute('advert-backend', [], true);
        }

        var_dump($advert);
        exit;
    }
}
