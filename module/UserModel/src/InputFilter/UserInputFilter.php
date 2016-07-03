<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserModel\InputFilter;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\InputFilter;
use Zend\Validator\EmailAddress;
use Zend\Validator\InArray;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

/**
 * Class UserInputFilter
 *
 * @package UserModel\InputFilter
 */
class UserInputFilter extends InputFilter
    implements UserInputFilterInterface
{
    /**
     * @var array
     */
    private $statusOptions;

    /**
     * @param array $statusOptions
     */
    public function setStatusOptions($statusOptions)
    {
        $this->statusOptions = $statusOptions;
    }

    /**
     * @var array
     */
    private $roleOptions;

    /**
     * @param array $roleOptions
     */
    public function setRoleOptions($roleOptions)
    {
        $this->roleOptions = $roleOptions;
    }

    /**
     * Init input filter
     */
    public function init()
    {
        $this->add(
            [
                'name'       => 'status',
                'required'   => true,
                'filters'    => [],
                'validators' => [
                    [
                        'name'                   => NotEmpty::class,
                        'break_chain_on_failure' => true,
                        'options'                => [
                            'message' => 'user_model_message_status_missing',
                        ],
                    ],
                    [
                        'name'    => InArray::class,
                        'options' => [
                            'haystack' => $this->statusOptions,
                            'message'  => 'user_model_message_status_invalid',
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'role',
                'required'   => true,
                'filters'    => [],
                'validators' => [
                    [
                        'name'                   => NotEmpty::class,
                        'break_chain_on_failure' => true,
                        'options'                => [
                            'message' => 'user_model_message_role_missing',
                        ],
                    ],
                    [
                        'name'    => InArray::class,
                        'options' => [
                            'haystack' => $this->roleOptions,
                            'message'  => 'user_model_message_role_invalid',
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'email',
                'required'   => true,
                'filters'    => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    [
                        'name'                   => NotEmpty::class,
                        'break_chain_on_failure' => true,
                        'options'                => [
                            'message' => 'user_model_message_email_missing',
                        ],
                    ],
                    [
                        'name'    => EmailAddress::class,
                        'options' => [
                            'message' => 'user_model_message_email_invalid',
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'password',
                'required'   => true,
                'filters'    => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    [
                        'name'                   => NotEmpty::class,
                        'break_chain_on_failure' => true,
                        'options'                => [
                            'message' => 'user_model_message_password_missing',
                        ],
                    ],
                    [
                        'name'    => StringLength::class,
                        'options' => [
                            'min'     => 8,
                            'max'     => 16,
                            'message' => 'user_model_message_password_invalid',
                        ],
                    ],
                ],
            ]
        );
    }
}
