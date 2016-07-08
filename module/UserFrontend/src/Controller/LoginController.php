<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontend\Controller;

use UserFrontend\Form\UserLoginFormInterface;
use UserModel\Repository\UserRepositoryInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Model\ViewModel;

/**
 * Class LoginController
 *
 * @package UserFrontend\Controller
 * @method Request getRequest()
 * @method FlashMessenger flashMessenger()
 */
class LoginController extends AbstractActionController
{
    /**
     * @var UserLoginFormInterface
     */
    private $userForm;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @param UserLoginFormInterface $userForm
     */
    public function setUserForm(UserLoginFormInterface $userForm)
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
     * Login user
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
                'user_frontend_message_login'
            );
        }
        
        $viewModel = new ViewModel();

        return $viewModel;
    }
}