<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertModel\InputFilter;

use Zend\InputFilter\InputFilter;

/**
 * Class AdvertInputFilter
 *
 * @package AdvertModel\InputFilter
 */
class AdvertInputFilter extends InputFilter
    implements AdvertInputFilterInterface
{
    /**
     * @var array
     */
    private $statusOptions;

    /**
     * @var array
     */
    private $typeOptions;

    /**
     * @var array
     */
    private $companyOptions;

    /**
     * @param array $statusOptions
     */
    public function setStatusOptions($statusOptions)
    {
        $this->statusOptions = $statusOptions;
    }

    /**
     * @param array $typeOptions
     */
    public function setTypeOptions($typeOptions)
    {
        $this->typeOptions = $typeOptions;
    }

    /**
     * @param array $companyOptions
     */
    public function setCompanyOptions($companyOptions)
    {
        $this->companyOptions = $companyOptions;
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
                        'name'                   => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options'                => [
                            'message' => 'advert_model_message_status_missing',
                        ],
                    ],
                    [
                        'name'    => 'InArray',
                        'options' => [
                            'haystack' => $this->statusOptions,
                            'message'  => 'advert_model_message_status_invalid',
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'type',
                'required'   => true,
                'filters'    => [],
                'validators' => [
                    [
                        'name'                   => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options'                => [
                            'message' => 'advert_model_message_type_missing',
                        ],
                    ],
                    [
                        'name'    => 'InArray',
                        'options' => [
                            'haystack' => $this->typeOptions,
                            'message'  => 'advert_model_message_type_invalid',
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'company',
                'required'   => true,
                'filters'    => [],
                'validators' => [
                    [
                        'name'                   => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options'                => [
                            'message' => 'advert_model_message_company_missing',
                        ],
                    ],
                    [
                        'name'    => 'InArray',
                        'options' => [
                            'haystack' => $this->companyOptions,
                            'message'  => 'advert_model_message_company_invalid',
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'title',
                'required'   => true,
                'filters'    => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name'                   => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options'                => [
                            'message' => 'advert_model_message_title_missing',
                        ],
                    ],
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min'      => 3,
                            'max'      => 64,
                            'message'  => 'advert_model_message_title_invalid',
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'text',
                'required'   => true,
                'filters'    => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StringHtmlPurify'],
                ],
                'validators' => [
                    [
                        'name'                   => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options'                => [
                            'message' => 'advert_model_message_text_missing',
                        ],
                    ],
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min'      => 200,
                            'message'  => 'advert_model_message_text_invalid',
                        ],
                    ],
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'location',
                'required'   => true,
                'filters'    => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name'                   => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options'                => [
                            'message' => 'advert_model_message_location_missing',
                        ],
                    ],
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min'      => 3,
                            'max'      => 64,
                            'message'  => 'advert_model_message_location_invalid',
                        ],
                    ],
                ],
            ]
        );
    }
}
