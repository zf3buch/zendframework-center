<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontend\Controller;

use UserFrontend\Form\UserRegisterFormInterface;
use UserModel\Repository\UserRepositoryInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Model\ViewModel;

/**
 * Class RegisterController
 *
 * @package UserFrontend\Controller
 * @method Request getRequest()
 * @method FlashMessenger flashMessenger()
 */
class RegisterController extends AbstractActionController
{
    /**
     * @var UserRegisterFormInterface
     */
    private $userForm;
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * Register user
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            $this->userForm->setData($this->params()->fromPost());

            if ($this->userForm->isValid()) {
                $user = $this->userRepository->createUserFromData(
                    $this->userForm->getData()
                );
                $user->encryptPassword();

                $result = $this->userRepository->saveUser($user);

                if ($result) {
                    $this->flashMessenger()->addSuccessMessage(
                        'user_frontend_message_registered'
                    );

                    return $this->redirect()->toRoute(
                        'user-frontend/login', [], true
                    );
                }
            }

            $messages = $this->userForm->getMessages();

            if (isset($messages['csrf'])) {
                $this->flashMessenger()->addErrorMessage(
                    'user_frontend_message_form_timeout'
                );
            } else {
                $this->flashMessenger()->addErrorMessage(
                    'user_frontend_message_check_data'
                );
            }
        } else {
            $this->flashMessenger()->addInfoMessage(
                'user_frontend_message_register'
            );
        }

        $this->userForm->setAttribute(
            'action',
            $this->url()->fromRoute(
                'user-frontend/register', [], true
            )
        );
        $this->userForm->prepare();

        $viewModel = new ViewModel();
        $viewModel->setVariable('userForm', $this->userForm);

        return $viewModel;
    }

    /**
     * @param UserRegisterFormInterface $userForm
     */
    public function setUserForm(UserRegisterFormInterface $userForm)
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
}

// 000000007f40b7b000007f6ab78e01cb
// 000000007f40b74600007f6ab78e01cb