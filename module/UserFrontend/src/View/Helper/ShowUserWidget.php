<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontend\View\Helper;

use UserModel\Entity\UserEntity;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

/**
 * Class ShowUserWidget
 *
 * @package UserFrontend\View\Helper
 */
class ShowUserWidget extends AbstractHelper
{
    /**
     * @var UserEntity|null
     */
    private $identity;

    /**
     * @param null|UserEntity $identity
     */
    public function setIdentity($identity = null)
    {
        $this->identity = $identity;
    }

    /**
     * Output the user widget
     */
    public function __invoke()
    {
        $viewModel = new ViewModel();
        $viewModel->setVariable('identity', $this->identity);
        $viewModel->setTemplate('user-frontend/widget/user-widget');

        return $this->getView()->render($viewModel);
    }
}
