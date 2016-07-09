<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserBackend\Form;

use Zend\Form\Element\Csrf;
use Zend\Form\Element\Password;
use Zend\Form\Element\Select;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Class UserForm
 *
 * @package UserBackend\Form
 */
class UserForm extends Form implements UserFormInterface
{
    /**
     * @var array
     */
    private $statusOptions;

    /**
     * @var array
     */
    private $roleOptions;

    /**
     * @param array $statusOptions
     */
    public function setStatusOptions($statusOptions)
    {
        $this->statusOptions = $statusOptions;
    }

    /**
     * @param array $roleOptions
     */
    public function setRoleOptions($roleOptions)
    {
        $this->roleOptions = $roleOptions;
    }

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
                'type'       => Select::class,
                'name'       => 'status',
                'attributes' => [
                    'class' => 'form-control',
                ],
                'options'    => [
                    'value_options' => $this->statusOptions,
                    'label'         => 'user_backend_label_status',
                ],
            ]
        );

        $this->add(
            [
                'type'       => Select::class,
                'name'       => 'role',
                'attributes' => [
                    'class' => 'form-control',
                ],
                'options'    => [
                    'value_options' => $this->roleOptions,
                    'label'         => 'user_backend_label_role',
                ],
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
                    'label' => 'user_backend_label_email',
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
                    'label' => 'user_backend_label_password',
                ],
            ]
        );

        $this->add(
            [
                'type'       => Submit::class,
                'name'       => 'save_user',
                'options'    => [],
                'attributes' => [
                    'id'    => 'save_user',
                    'class' => 'btn btn-primary',
                    'value' => 'user_backend_action_save',
                ],
            ]
        );
    }

    /**
     * Switch to edit mode
     */
    public function editMode()
    {
        if ($this->has('status')) {
            $this->remove('status');
        }

        if ($this->has('role')) {
            $this->remove('role');
        }

        if ($this->has('password')) {
            $this->getInputFilter()->get('password')->setRequired(false);
        }

        $this->setValidationGroup(array_keys($this->getElements()));
    }
}
