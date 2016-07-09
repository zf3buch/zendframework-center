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
 * Class UserEditForm
 *
 * @package UserFrontend\Form
 */
class UserEditForm extends AbstractUserForm
    implements UserEditFormInterface
{
    /**
     * Init form
     */
    public function init()
    {
        $this->setName('user_edit_form');
        $this->setAttribute('class', 'form-horizontal');

        $this->addCsrfElement();
        $this->addEmailElement();
        $this->addPasswordElement();
        $this->addSubmitElement(
            'edit_user', 'user_frontend_action_edit'
        );

        if ($this->getInputFilter()->has('password')) {
            $this->getInputFilter()->get('password')->setRequired(false);
        }

        $this->setValidationGroup(array_keys($this->getElements()));
    }
}
