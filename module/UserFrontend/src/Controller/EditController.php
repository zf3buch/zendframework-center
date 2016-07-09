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
use UserModel\Entity\UserEntity;
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
 * @method UserEntity|null identity()
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
        $user = $this->identity();

        if (!$user) {
            return $this->redirect()->toRoute('user-frontend');
        }

        $this->userForm->bind($user);

        if ($this->getRequest()->isPost()) {
            $this->userForm->setData($this->params()->fromPost());

            if ($this->userForm->isValid()) {
                $user->update();
                $user->encryptPassword();

                $result = $this->userRepository->saveUser($user);

                if ($result) {
                    $this->flashMessenger()->addSuccessMessage(
                        'user_frontend_message_edited'
                    );

                    return $this->redirect()->toRoute(
                        'user-frontend/edit', [], true
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
                'user_frontend_message_edit'
            );
        }

        $this->userForm->setAttribute(
            'action',
            $this->url()->fromRoute(
                'user-frontend/edit', [], true
            )
        );
        $this->userForm->prepare();

        $viewModel = new ViewModel();
        $viewModel->setVariable('userForm', $this->userForm);

        return $viewModel;
    }
}