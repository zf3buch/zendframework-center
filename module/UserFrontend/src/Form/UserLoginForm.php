<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontend\Form;

/**
 * Class UserLoginForm
 *
 * @package UserFrontend\Form
 */
class UserLoginForm extends AbstractUserForm
    implements UserLoginFormInterface
{
    /**
     * Init form
     */
    public function init()
    {
        $this->setName('user_login_form');
        $this->setAttribute('class', 'form-horizontal');

        $this->addCsrfElement();
        $this->addEmailElement();
        $this->addPasswordElement();
        $this->addSubmitElement(
            'login_user', 'user_frontend_action_login'
        );

        $this->setValidationGroup(array_keys($this->getElements()));
    }
}
