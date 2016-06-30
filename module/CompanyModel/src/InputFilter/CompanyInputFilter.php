<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace CompanyModel\InputFilter;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilter;
use Zend\Validator\EmailAddress;
use Zend\Validator\File\ImageSize;
use Zend\Validator\File\MimeType;
use Zend\Validator\InArray;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

/**
 * Class CompanyInputFilter
 *
 * @package CompanyModel\InputFilter
 */
class CompanyInputFilter extends InputFilter
    implements CompanyInputFilterInterface
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
                            'message' => 'company_model_message_status_missing',
                        ],
                    ],
                    [
                        'name'    => InArray::class,
                        'options' => [
                            'haystack' => $this->statusOptions,
                            'message'  => 'company_model_message_status_invalid',
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'name',
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
                            'message' => 'company_model_message_name_missing',
                        ],
                    ],
                    [
                        'name'    => StringLength::class,
                        'options' => [
                            'min'      => 3,
                            'max'      => 64,
                            'message'  => 'company_model_message_name_invalid',
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
                            'message' => 'company_model_message_email_missing',
                        ],
                    ],
                    [
                        'name'    => EmailAddress::class,
                        'options' => [
                            'message' => 'company_model_message_email_invalid',
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'contact',
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
                            'message' => 'company_model_message_contact_missing',
                        ],
                    ],
                    [
                        'name'    => StringLength::class,
                        'options' => [
                            'min'      => 3,
                            'max'      => 64,
                            'message'  => 'company_model_message_contact_invalid',
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'logo',
                'type'       => FileInput::class,
                'required'   => false,
                'filters'    => [],
                'validators' => [
                    [
                        'name'    => MimeType::class,
                        'options' => [
                            'mimeType' => 'image/png,image/x-png',
                            'message'  => 'company_model_message_logo_type',
                        ],
                    ],
                    [
                        'name'    => ImageSize::class,
                        'options' => [
                            'minWidth'  => '200',
                            'maxWidth'  => '200',
                            'minHeight' => '100',
                            'maxHeight' => '100',
                            'message'   => 'company_model_message_logo_size',
                        ],
                    ],
                ],
            ]
        );
    }
}
