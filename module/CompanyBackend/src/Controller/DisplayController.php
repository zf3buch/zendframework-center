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
use Zend\Debug\Debug;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class DisplayController
 *
 * @package CompanyBackend\Controller
 */
class DisplayController extends AbstractActionController
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
     * Show company list
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $page = $this->params()->fromRoute('page', 1);

        $companyList = $this->companyRepository->getCompaniesByPage(
            $page, 15
        );

        if (!$companyList) {
            return $this->redirect()->toRoute('company-backend', [], true);
        }

        $viewModel = new ViewModel();
        $viewModel->setVariable('companyList', $companyList);

        return $viewModel;
    }

    /**
     * Show company
     *
     * @return ViewModel
     */
    public function showAction()
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
