<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace CompanyBackend\Controller;

use CompanyModel\Repository\CompanyRepositoryInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class ModifyController
 *
 * @package CompanyBackend\Controller
 */
class ModifyController extends AbstractActionController
{
    /**
     * @var CompanyRepositoryInterface
     */
    private $companyRepository;

    /**
     * @param CompanyRepositoryInterface $companyRepository
     */
    public function setCompanyRepository($companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    /**
     * Add new company
     *
     * @return ViewModel
     */
    public function addAction()
    {
        $viewModel = new ViewModel();

        return $viewModel;
    }

    /**
     * Edit exiting company
     *
     * @return ViewModel
     */
    public function editAction()
    {
        $id = $this->params()->fromRoute('id');

        if (!$id) {
            return $this->redirect()->toRoute('company-backend', [], true);
        }

        $company = $this->companyRepository->getSingleCompanyById($id);

        if (!$company) {
            return $this->redirect()->toRoute('company-backend', [], true);
        }

        $viewModel = new ViewModel();
        $viewModel->setVariable('company', $company);

        return $viewModel;
    }

    /**
     * Delete existing company
     *
     * @return ViewModel
     */
    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');

        if (!$id) {
            return $this->redirect()->toRoute('company-backend', [], true);
        }

        $company = $this->companyRepository->getSingleCompanyById($id);

        if (!$company) {
            return $this->redirect()->toRoute('company-backend', [], true);
        }

        $viewModel = new ViewModel();
        $viewModel->setVariable('company', $company);

        return $viewModel;
    }

    /**
     * Approve existing company
     *
     * @return ViewModel
     */
    public function approveAction()
    {
        $id = $this->params()->fromRoute('id');

        if (!$id) {
            return $this->redirect()->toRoute('company-backend', [], true);
        }

        $company = $this->companyRepository->getSingleCompanyById($id);

        if (!$company) {
            return $this->redirect()->toRoute('company-backend', [], true);
        }

        $viewModel = new ViewModel();
        $viewModel->setVariable('company', $company);

        return $viewModel;
    }

    /**
     * block exiting company
     *
     * @return ViewModel
     */
    public function blockAction()
    {
        $id = $this->params()->fromRoute('id');

        if (!$id) {
            return $this->redirect()->toRoute('company-backend', [], true);
        }

        $company = $this->companyRepository->getSingleCompanyById($id);

        if (!$company) {
            return $this->redirect()->toRoute('company-backend', [], true);
        }

        $viewModel = new ViewModel();
        $viewModel->setVariable('company', $company);

        return $viewModel;
    }
}
