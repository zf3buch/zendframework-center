<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontend\View\Helper;

use UserFrontend\Form\UserFormInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Class AbstractShowForm
 *
 * @package UserFrontend\View\Helper
 */
abstract class AbstractShowForm extends AbstractHelper
{
    /**
     * @var UserFormInterface
     */
    private $userForm;

    /**
     * @return UserFormInterface
     */
    protected function getUserForm()
    {
        return $this->userForm;
    }

    /**
     * @param UserFormInterface $userForm
     */
    public function setUserForm($userForm)
    {
        $this->userForm = $userForm;
    }
}
