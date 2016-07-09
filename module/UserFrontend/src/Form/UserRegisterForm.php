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
 * Class UserRegisterForm
 *
 * @package UserFrontend\Form
 */
class UserRegisterForm extends AbstractUserForm
    implements UserRegisterFormInterface
{
    /**
     * Init form
     */
    public function init()
    {
        $this->setName('user_register_form');
        $this->setAttribute('class', 'form-horizontal');

        $this->addCsrfElement();
        $this->addEmailElement();
        $this->addPasswordElement();
        $this->addSubmitElement(
            'register_user', 'user_frontend_action_register'
        );

        $this->setValidationGroup(array_keys($this->getElements()));
    }
}
