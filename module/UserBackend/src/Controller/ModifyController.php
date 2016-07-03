<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserBackend\Controller;

use UserBackend\Form\UserFormInterface;
use UserModel\Repository\UserRepositoryInterface;
use Zend\Form\Form;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Model\ViewModel;

/**
 * Class ModifyController
 *
 * @package UserBackend\Controller
 * @method Request getRequest()
 * @method FlashMessenger flashMessenger()
 */
class ModifyController extends AbstractActionController
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var UserFormInterface|Form
     */
    private $userForm;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function setUserRepository($userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UserFormInterface $userForm
     */
    public function setUserForm($userForm)
    {
        $this->userForm = $userForm;
    }

    /**
     * Add new user
     *
     * @return ViewModel
     */
    public function addAction()
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
                        'user_backend_message_saved_user'
                    );

                    return $this->redirect()->toRoute(
                        'user-backend/modify',
                        [
                            'action' => 'edit',
                            'id'     => $user->getId(),
                        ],
                        true
                    );
                }
            }

            $messages = $this->userForm->getMessages();

            if (isset($messages['csrf'])) {
                $this->flashMessenger()->addErrorMessage(
                    'user_backend_message_form_timeout'
                );
            } else {
                $this->flashMessenger()->addErrorMessage(
                    'user_backend_message_check_data'
                );
            }
        } else {
            $this->flashMessenger()->addInfoMessage(
                'user_backend_message_create_user'
            );
        }

        $this->userForm->setAttribute(
            'action',
            $this->url()->fromRoute(
                'user-backend/modify', ['action' => 'add'], true
            )
        );
        $this->userForm->prepare();

        $viewModel = new ViewModel();
        $viewModel->setVariable('userForm', $this->userForm);

        return $viewModel;
    }

    /**
     * Edit exiting user
     *
     * @return ViewModel
     */
    public function editAction()
    {
        $this->userForm->editMode();

        $id = $this->params()->fromRoute('id');

        if (!$id) {
            return $this->redirect()->toRoute('user-backend', [], true);
        }

        $user = $this->userRepository->getSingleUserById($id);

        if (!$user) {
            return $this->redirect()->toRoute('user-backend', [], true);
        }

        $this->userForm->bind($user);

        if ($this->getRequest()->isPost()) {
            $postData  = $this->params()->fromPost();

            $this->userForm->setData($postData);

            if ($this->userForm->isValid()) {
                $user->update();
                $user->encryptPassword();

                $result = $this->userRepository->saveUser($user);

                if ($result) {
                    $this->flashMessenger()->addSuccessMessage(
                        'user_backend_message_saved_user'
                    );

                    return $this->redirect()->toRoute(
                        'user-backend/modify',
                        [
                            'action' => 'edit',
                            'id'     => $user->getId(),
                        ],
                        true
                    );
                }
            }

            $messages = $this->userForm->getMessages();

            if (isset($messages['csrf'])) {
                $this->flashMessenger()->addErrorMessage(
                    'user_backend_message_form_timeout'
                );
            } else {
                $this->flashMessenger()->addErrorMessage(
                    'user_backend_message_check_data'
                );
            }
        } else {
            $this->flashMessenger()->addInfoMessage(
                'user_backend_message_update_user'
            );
        }

        $this->userForm->setAttribute(
            'action',
            $this->url()->fromRoute(
                'user-backend/modify',
                ['action' => 'edit', 'id' => $user->getId()],
                true
            )
        );
        $this->userForm->prepare();

        $viewModel = new ViewModel();
        $viewModel->setVariable('user', $user);
        $viewModel->setVariable('userForm', $this->userForm);

        return $viewModel;
    }

    /**
     * Delete existing user
     *
     * @return ViewModel
     */
    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id');

        if (!$id) {
            return $this->redirect()->toRoute('user-backend', [], true);
        }

        $user = $this->userRepository->getSingleUserById($id);

        if (!$user) {
            return $this->redirect()->toRoute('user-backend', [], true);
        }

        $delete = $this->params()->fromQuery('delete', 'no');

        if ($delete == 'yes') {
            $this->userRepository->deleteUser($user);

            $this->flashMessenger()->addSuccessMessage(
                'user_backend_message_deleted_user'
            );

            return $this->redirect()->toRoute('user-backend', [], true);
        }

        $viewModel = new ViewModel();
        $viewModel->setVariable('user', $user);

        return $viewModel;
    }

    /**
     * Approve existing user
     *
     * @return ViewModel
     */
    public function approveAction()
    {
        $id = $this->params()->fromRoute('id');

        if (!$id) {
            return $this->redirect()->toRoute('user-backend', [], true);
        }

        $user = $this->userRepository->getSingleUserById($id);

        if (!$user) {
            return $this->redirect()->toRoute('user-backend', [], true);
        }

        $approve = $this->params()->fromQuery('approve', 'no');

        if ($approve == 'yes') {
            $user->approve();

            $this->userRepository->saveUser($user);

            $this->flashMessenger()->addSuccessMessage(
                'user_backend_message_approved_user'
            );

            return $this->redirect()->toRoute(
                'user-backend/show', ['id' => $user->getId()], true
            );
        }

        $viewModel = new ViewModel();
        $viewModel->setVariable('user', $user);

        return $viewModel;
    }

    /**
     * block exiting user
     *
     * @return ViewModel
     */
    public function blockAction()
    {
        $id = $this->params()->fromRoute('id');

        if (!$id) {
            return $this->redirect()->toRoute('user-backend', [], true);
        }

        $user = $this->userRepository->getSingleUserById($id);

        if (!$user) {
            return $this->redirect()->toRoute('user-backend', [], true);
        }

        $block = $this->params()->fromQuery('block', 'no');

        if ($block == 'yes') {
            $user->block();

            $this->userRepository->saveUser($user);

            $this->flashMessenger()->addSuccessMessage(
                'user_backend_message_blocked_user'
            );

            return $this->redirect()->toRoute(
                'user-backend/show', ['id' => $user->getId()], true
            );
        }

        $viewModel = new ViewModel();
        $viewModel->setVariable('user', $user);

        return $viewModel;
    }
}
