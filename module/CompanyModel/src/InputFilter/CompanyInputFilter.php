<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace CompanyModel\InputFilter;

use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilter;
use Zend\Validator\File\ImageSize;
use Zend\Validator\File\MimeType;

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
                        'name'                   => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options'                => [
                            'message' => 'Bitte Status eingeben!',
                        ],
                    ],
                    [
                        'name'    => 'InArray',
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
                'name'       => 'name',
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
                            'message' => 'Bitte Firmenname eingeben!',
                        ],
                    ],
                    [
                        'name'    => 'StringLength',
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
                'name'       => 'email',
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
                            'message' => 'Bitte E-Mail Adresse eingeben!',
                        ],
                    ],
                    [
                        'name'    => 'EmailAddress',
                        'options' => [
                            'message' => 'Bitte gültige E-Mail eingeben!',
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
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name'                   => 'NotEmpty',
                        'break_chain_on_failure' => true,
                        'options'                => [
                            'message' => 'Bitte Ansprechpartner eingeben!',
                        ],
                    ],
                    [
                        'name'    => 'StringLength',
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
                'name'       => 'logo',
                'type'       => FileInput::class,
                'required'   => false,
                'filters'    => [],
                'validators' => [
                    [
                        'name'    => MimeType::class,
                        'options' => [
                            'mimeType' => 'image/png,image/x-png',
                            'message'  => 'Nur PNG Grafiken erlaubt!',
                        ],
                    ],
                    [
                        'name'    => ImageSize::class,
                        'options' => [
                            'minWidth'  => '200',
                            'maxWidth'  => '200',
                            'minHeight' => '100',
                            'maxHeight' => '100',
                            'message'   => 'Nur 200x100 Pixel erlaubt!',
                        ],
                    ],
                ],
            ]
        );
    }
}
