<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontend\Form;

use Zend\Form\Element\Csrf;
use Zend\Form\Element\Password;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Class UserForm
 *
 * @package UserFrontend\Form
 */
class UserForm extends Form implements UserFormInterface
{
    /**
     * Init form
     */
    public function init()
    {
        $this->setName('user_form');
        $this->setAttribute('class', 'form-horizontal');

        $this->add(
            [
                'type' => Csrf::class,
                'name' => 'csrf',
            ]
        );

        $this->add(
            [
                'type'       => Text::class,
                'name'       => 'email',
                'attributes' => [
                    'class' => 'form-control',
                ],
                'options'    => [
                    'label'            => 'user_frontend_label_email',
                    'label_attributes' => [
                        'class' => 'col-sm-2 control-label',
                    ],
                ],
            ]
        );

        $this->add(
            [
                'type'       => Password::class,
                'name'       => 'password',
                'attributes' => [
                    'class' => 'form-control',
                ],
                'options'    => [
                    'label'            => 'user_frontend_label_password',
                    'label_attributes' => [
                        'class' => 'col-sm-2 control-label',
                    ],
                ],
            ]
        );
    }

    /**
     * Switch to edit mode
     */
    public function editMode()
    {
        if ($this->has('password')) {
            $this->getInputFilter()->get('password')->setRequired(false);
        }

        $this->addSubmitElement('edit_user', 'user_frontend_action_edit');
    }

    /**
     * Switch to login mode
     */
    public function loginMode()
    {
        $this->addSubmitElement(
            'login_user', 'user_frontend_action_login'
        );
    }

    /**
     * Switch to register mode
     */
    public function registerMode()
    {
        $this->addSubmitElement(
            'register_user', 'user_frontend_action_register'
        );
    }

    /**
     * Add new submit element
     *
     * @param $submitName
     * @param $submitText
     */
    private function addSubmitElement($submitName, $submitText)
    {
        $this->add(
            [
                'type'       => Submit::class,
                'name'       => $submitName,
                'options'    => [],
                'attributes' => [
                    'id'    => $submitName,
                    'class' => 'btn btn-primary',
                    'value' => $submitText,
                ],
            ]
        );

        $this->setValidationGroup(array_keys($this->getElements()));
    }
}
