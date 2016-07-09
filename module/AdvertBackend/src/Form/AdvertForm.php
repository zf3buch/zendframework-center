<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertBackend\Form;

use Zend\Form\Element\Csrf;
use Zend\Form\Element\Select;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
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
                'type' => Csrf::class,
                'name' => 'csrf',
            ]
        );

        $this->add(
            [
                'type'       => Select::class,
                'name'       => 'type',
                'attributes' => [
                    'class' => 'form-control',
                ],
                'options'    => [
                    'value_options' => $this->typeOptions,
                    'label'         => 'advert_backend_label_type',
                ],
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
                    'label'         => 'advert_backend_label_status',
                ],
            ]
        );

        $this->add(
            [
                'type'       => Select::class,
                'name'       => 'company',
                'attributes' => [
                    'class' => 'form-control',
                ],
                'options'    => [
                    'value_options' => $this->companyOptions,
                    'label'         => 'advert_backend_label_company',
                ],
            ]
        );

        $this->add(
            [
                'type'       => Text::class,
                'name'       => 'location',
                'attributes' => [
                    'class' => 'form-control',
                ],
                'options'    => [
                    'label' => 'advert_backend_label_location',
                ],
            ]
        );

        $this->add(
            [
                'type'       => Text::class,
                'name'       => 'title',
                'attributes' => [
                    'class' => 'form-control',
                ],
                'options'    => [
                    'label' => 'advert_backend_label_title',
                ],
            ]
        );

        $this->add(
            [
                'type'       => Textarea::class,
                'name'       => 'text',
                'attributes' => [
                    'id'    => 'advert_text',
                    'class' => 'form-control',
                ],
                'options'    => [
                    'label' => 'advert_backend_label_text',
                ],
            ]
        );

        $this->add(
            [
                'type'       => Submit::class,
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
