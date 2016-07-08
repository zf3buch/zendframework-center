<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontend\Controller;

use UserModel\Entity\UserEntity;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\Identity\Identity;
use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 *
 * @package UserFrontend\Controller
 * @method UserEntity|null identity()
 */
class IndexController extends AbstractActionController
{
    /**
     * Show user intro
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        if ($this->identity()) {
            return $this->redirect()->toRoute('user-frontend/edit');
        }

        $viewModel = new ViewModel();

        return $viewModel;
    }
}
