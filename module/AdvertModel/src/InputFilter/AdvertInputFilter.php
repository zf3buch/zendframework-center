<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertModel\InputFilter;

use TravelloFilter\Filter\StringHtmlPurify;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\InputFilter;
use Zend\Validator\InArray;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

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
                        'name'                   => NotEmpty::class,
                        'break_chain_on_failure' => true,
                        'options'                => [
                            'message' => 'Bitte Status eingeben!',
                        ],
                    ],
                    [
                        'name'    => InArray::class,
                        'options' => [
                            'haystack' => $this->statusOptions,
                            'message'  => 'Ungültiger Status!',
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
                        'name'                   => NotEmpty::class,
                        'break_chain_on_failure' => true,
                        'options'                => [
                            'message' => 'Bitte Typ der Annonce eingeben!',
                        ],
                    ],
                    [
                        'name'    => InArray::class,
                        'options' => [
                            'haystack' => $this->typeOptions,
                            'message'  => 'Ungültiger Annoncentyp!',
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
                        'name'                   => NotEmpty::class,
                        'break_chain_on_failure' => true,
                        'options'                => [
                            'message' => 'Bitte Status eingeben!',
                        ],
                    ],
                    [
                        'name'    => InArray::class,
                        'options' => [
                            'haystack' => $this->companyOptions,
                            'message'  => 'Unbekanntes Unternehmen!',
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
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    [
                        'name'                   => NotEmpty::class,
                        'break_chain_on_failure' => true,
                        'options'                => [
                            'message' => 'Bitte Annoncentitel eingeben!',
                        ],
                    ],
                    [
                        'name'    => StringLength::class,
                        'options' => [
                            'min'      => 3,
                            'max'      => 64,
                            'message'  => 'Nur %min%-%max% Zeichen erlaubt!',
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
                    ['name' => StringTrim::class],
                    ['name' => StringHtmlPurify::class],
                ],
                'validators' => [
                    [
                        'name'                   => NotEmpty::class,
                        'break_chain_on_failure' => true,
                        'options'                => [
                            'message' => 'Bitte Text der Annonce eingeben!',
                        ],
                    ],
                    [
                        'name'    => StringLength::class,
                        'options' => [
                            'min'      => 200,
                            'message'  => 'Mindestens %min% Zeichen eingeben!',
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
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    [
                        'name'                   => NotEmpty::class,
                        'break_chain_on_failure' => true,
                        'options'                => [
                            'message' => 'Bitte Ort eingeben!',
                        ],
                    ],
                    [
                        'name'    => StringLength::class,
                        'options' => [
                            'min'      => 3,
                            'max'      => 64,
                            'message'  => 'Nur %min%-%max% Zeichen erlaubt!',
                        ],
                    ],
                ],
            ]
        );
    }
}
