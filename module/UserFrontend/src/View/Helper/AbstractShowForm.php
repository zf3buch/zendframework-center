<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontend\View\Helper;

use UserFrontend\Form\AbstractUserForm;
use Zend\View\Helper\AbstractHelper;

/**
 * Class AbstractShowForm
 *
 * @package UserFrontend\View\Helper
 */
abstract class AbstractShowForm extends AbstractHelper
{
    /**
     * @var AbstractUserForm
     */
    private $userForm;

    /**
     * @return AbstractUserForm
     */
    protected function getUserForm()
    {
        return $this->userForm;
    }

    /**
     * @param AbstractUserForm $userForm
     */
    public function setUserForm(AbstractUserForm $userForm)
    {
        $this->userForm = $userForm;
    }
}
