<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertBackend\Form;

use Zend\Form\Form;

/**
 * Class AdvertForm
 *
 * @package AdvertBackend\Form
 */
class AdvertForm extends Form implements AdvertFormInterface
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
     * Init form
     */
    public function init()
    {
        $this->setName('advert_form');
        $this->setAttribute('class', 'form-horizontal');

        $this->add(
            [
                'type' => 'Csrf',
                'name' => 'csrf',
            ]
        );

        $this->add(
            [
                'type'       => 'Select',
                'name'       => 'type',
                'attributes' => [
                    'class' => 'form-control',
                ],
                'options'    => [
                    'value_options'    => $this->typeOptions,
                    'label'            => 'advert_backend_label_type',
                    'label_attributes' => [
                        'class' => 'col-sm-2 control-label',
                    ],
                ],
            ]
        );

        $this->add(
            [
                'type'       => 'Select',
                'name'       => 'status',
                'attributes' => [
                    'class' => 'form-control',
                ],
                'options'    => [
                    'value_options'    => $this->statusOptions,
                    'label'            => 'advert_backend_label_status',
                    'label_attributes' => [
                        'class' => 'col-sm-2 control-label',
                    ],
                ],
            ]
        );

        $this->add(
            [
                'type'       => 'Select',
                'name'       => 'company',
                'attributes' => [
                    'class' => 'form-control',
                ],
                'options'    => [
                    'value_options'    => $this->companyOptions,
                    'label'            => 'advert_backend_label_company',
                    'label_attributes' => [
                        'class' => 'col-sm-2 control-label',
                    ],
                ],
            ]
        );

        $this->add(
            [
                'type'       => 'Text',
                'name'       => 'location',
                'attributes' => [
                    'class' => 'form-control',
                ],
                'options'    => [
                    'label'            => 'advert_backend_label_location',
                    'label_attributes' => [
                        'class' => 'col-sm-2 control-label',
                    ],
                ],
            ]
        );

        $this->add(
            [
                'type'       => 'Text',
                'name'       => 'title',
                'attributes' => [
                    'class' => 'form-control',
                ],
                'options'    => [
                    'label'            => 'advert_backend_label_title',
                    'label_attributes' => [
                        'class' => 'col-sm-2 control-label',
                    ],
                ],
            ]
        );

        $this->add(
            [
                'type'       => 'Textarea',
                'name'       => 'text',
                'attributes' => [
                    'id'    => 'advert_text',
                    'class' => 'form-control',
                ],
                'options'    => [
                    'label'            => 'advert_backend_label_text',
                    'label_attributes' => [
                        'class' => 'col-sm-2 control-label',
                    ],
                ],
            ]
        );

        $this->add(
            [
                'type'       => 'Submit',
                'name'       => 'save_advert',
                'options'    => [],
                'attributes' => [
                    'id'    => 'save_advert',
                    'class' => 'btn btn-primary',
                    'value' => 'advert_backend_action_save',
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

        if ($this->has('type')) {
            $this->remove('type');
        }

        if ($this->has('company')) {
            $this->remove('company');
        }

        $this->setValidationGroup(array_keys($this->getElements()));
    }
}
