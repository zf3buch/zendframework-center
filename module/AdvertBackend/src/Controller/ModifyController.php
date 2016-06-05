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
 * Class ModifyController
 *
 * @package AdvertBackend\Controller
 */
class ModifyController extends AbstractActionController
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
    public function addAction()
    {
        $viewModel = new ViewModel();

        return $viewModel;
    }

    /**
     * @return ViewModel
     */
    public function editAction()
    {
        $id = $this->params()->fromRoute('id');

        if (!$id) {
            return $this->redirect()->toRoute('advert-backend', [], true);
        }

        $advert = $this->advertRepository->getSingleAdvertById($id);

        if (!$advert) {
            return $this->redirect()->toRoute('advert-backend', [], true);
        }

        $viewModel = new ViewModel();
        $viewModel->setVariable('advert', $advert);

        return $viewModel;
    }

    /**
     * @return ViewModel
     */
    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');

        if (!$id) {
            return $this->redirect()->toRoute('advert-backend', [], true);
        }

        $advert = $this->advertRepository->getSingleAdvertById($id);

        if (!$advert) {
            return $this->redirect()->toRoute('advert-backend', [], true);
        }

        $viewModel = new ViewModel();
        $viewModel->setVariable('advert', $advert);

        return $viewModel;
    }

    /**
     * @return ViewModel
     */
    public function approveAction()
    {
        $id = $this->params()->fromRoute('id');

        if (!$id) {
            return $this->redirect()->toRoute('advert-backend', [], true);
        }

        $advert = $this->advertRepository->getSingleAdvertById($id);

        if (!$advert) {
            return $this->redirect()->toRoute('advert-backend', [], true);
        }

        $viewModel = new ViewModel();
        $viewModel->setVariable('advert', $advert);

        return $viewModel;
    }

    /**
     * @return ViewModel
     */
    public function blockAction()
    {
        $id = $this->params()->fromRoute('id');

        if (!$id) {
            return $this->redirect()->toRoute('advert-backend', [], true);
        }

        $advert = $this->advertRepository->getSingleAdvertById($id);

        if (!$advert) {
            return $this->redirect()->toRoute('advert-backend', [], true);
        }

        $viewModel = new ViewModel();
        $viewModel->setVariable('advert', $advert);

        return $viewModel;
    }
}
