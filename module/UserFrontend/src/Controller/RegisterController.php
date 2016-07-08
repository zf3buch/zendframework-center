<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontend\Controller;

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
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function setUserRepository($userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register user
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $viewModel = new ViewModel();

        return $viewModel;
    }
}