<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertBackend\Controller;

use AdvertBackend\Form\AdvertFormInterface;
use AdvertModel\Repository\AdvertRepositoryInterface;
use Zend\Form\Form;
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
     * @var AdvertFormInterface|Form
     */
    private $advertForm;

    /**
     * @param AdvertRepositoryInterface $advertRepository
     */
    public function setAdvertRepository($advertRepository)
    {
        $this->advertRepository = $advertRepository;
    }

    /**
     * @param AdvertFormInterface $advertForm
     */
    public function setAdvertForm($advertForm)
    {
        $this->advertForm = $advertForm;
    }

    /**
     * @return ViewModel
     */
    public function addAction()
    {
        $this->advertForm->setAttribute(
            'action',
            $this->url()->fromRoute(
                'advert-backend/modify', ['action' => 'add'], true
            )
        );
        $this->advertForm->prepare();

        $viewModel = new ViewModel();
        $viewModel->setVariable('advertForm', $this->advertForm);

        return $viewModel;
    }

    /**
     * @return ViewModel
     */
    public function editAction()
    {
        $this->advertForm->editMode();

        $id = $this->params()->fromRoute('id');

        if (!$id) {
            return $this->redirect()->toRoute('advert-backend', [], true);
        }

        $advert = $this->advertRepository->getSingleAdvertById($id);

        if (!$advert) {
            return $this->redirect()->toRoute('advert-backend', [], true);
        }

        $this->advertForm->bind($advert);
        $this->advertForm->setAttribute(
            'action',
            $this->url()->fromRoute(
                'advert-backend/modify',
                ['action' => 'edit', 'id' => $id],
                true
            )
        );
        $this->advertForm->prepare();

        $viewModel = new ViewModel();
        $viewModel->setVariable('advert', $advert);
        $viewModel->setVariable('advertForm', $this->advertForm);

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
