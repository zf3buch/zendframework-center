<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontend\Controller;

use UserFrontend\Form\UserEditFormInterface;
use UserModel\Repository\UserRepositoryInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Model\ViewModel;

/**
 * Class EditController
 *
 * @package UserFrontend\Controller
 * @method Request getRequest()
 * @method FlashMessenger flashMessenger()
 */
class EditController extends AbstractActionController
{
    /**
     * @var UserEditFormInterface
     */
    private $userForm;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @param UserEditFormInterface $userForm
     */
    public function setUserForm(UserEditFormInterface $userForm)
    {
        $this->userForm = $userForm;
    }

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function setUserRepository(
        UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * Edit user
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            $this->flashMessenger()->addInfoMessage(
                'user_frontend_message_check_data'
            );
        } else {
            $this->flashMessenger()->addInfoMessage(
                'user_frontend_message_edit'
            );
        }

        $viewModel = new ViewModel();

        return $viewModel;
    }
}