<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace CompanyBackend\Form;

use CompanyModel\Filter\LogoFileUpload;
use TravelloFilter\Filter\StringToUrlSlug;
use Zend\Filter\StaticFilter;
use Zend\Form\Form;

/**
 * Class CompanyForm
 *
 * @package CompanyBackend\Form
 */
class CompanyForm extends Form implements CompanyFormInterface
{
    /**
     * @var array
     */
    private $statusOptions;

    /**
     * @var string
     */
    private $logoFilePath;

    /**
     * @var string
     */
    private $logoFilePattern;

    /**
     * @param array $statusOptions
     */
    public function setStatusOptions($statusOptions)
    {
        $this->statusOptions = $statusOptions;
    }

    /**
     * @param string $logoFilePath
     */
    public function setLogoFilePath($logoFilePath)
    {
        $this->logoFilePath = $logoFilePath;
    }

    /**
     * @param string $logoFilePattern
     */
    public function setLogoFilePattern($logoFilePattern)
    {
        $this->logoFilePattern = $logoFilePattern;
    }

    /**
     * Init form
     */
    public function init()
    {
        $this->setName('company_form');
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
                'name'       => 'status',
                'attributes' => [
                    'class' => 'form-control',
                ],
                'options'    => [
                    'value_options'    => $this->statusOptions,
                    'label'            => 'Status',
                    'label_attributes' => [
                        'class' => 'col-sm-2 control-label',
                    ],
                ],
            ]
        );

        $this->add(
            [
                'type'       => 'Text',
                'name'       => 'name',
                'attributes' => [
                    'class' => 'form-control',
                ],
                'options'    => [
                    'label'            => 'Firmenname',
                    'label_attributes' => [
                        'class' => 'col-sm-2 control-label',
                    ],
                ],
            ]
        );

        $this->add(
            [
                'type'       => 'Text',
                'name'       => 'email',
                'attributes' => [
                    'class' => 'form-control',
                ],
                'options'    => [
                    'label'            => 'E-Mail Adresse',
                    'label_attributes' => [
                        'class' => 'col-sm-2 control-label',
                    ],
                ],
            ]
        );

        $this->add(
            [
                'type'       => 'Text',
                'name'       => 'contact',
                'attributes' => [
                    'class' => 'form-control',
                ],
                'options'    => [
                    'label'            => 'Ansprechpartner',
                    'label_attributes' => [
                        'class' => 'col-sm-2 control-label',
                    ],
                ],
            ]
        );

        $this->add(
            [
                'type'       => 'File',
                'name'       => 'logo',
                'attributes' => [
                    'class' => 'form-control-static',
                ],
                'options'    => [
                    'label'            => 'Logo',
                    'label_attributes' => [
                        'class' => 'col-sm-2 control-label',
                    ],
                ],
            ]
        );

        $this->add(
            [
                'type'       => 'Submit',
                'name'       => 'save_company',
                'options'    => [],
                'attributes' => [
                    'id'    => 'save_company',
                    'class' => 'btn btn-primary',
                    'value' => 'Unternehmen speichern',
                ],
            ]
        );
    }

    /**
     * Switch to add mode
     */
    public function addMode()
    {
        if ($this->has('logo')) {
            $this->remove('logo');
        }

        $this->setValidationGroup(array_keys($this->getElements()));
    }

    /**
     * Switch to edit mode
     */
    public function editMode()
    {
        if ($this->has('status')) {
            $this->remove('status');
        }

        $this->setValidationGroup(array_keys($this->getElements()));
    }

    /**
     * Add logo file upload filter to input filter
     */
    public function addLogoFileUploadFilter()
    {
        $nameValue = $this->get('name')->getValue();

        $targetFile = sprintf(
            $this->logoFilePattern,
            StaticFilter::execute($nameValue, StringToUrlSlug::class)
        );

        $logoFileUploadFilter = new LogoFileUpload(
            $this->logoFilePath, $targetFile
        );

        $this->getInputFilter()->get('logo')->getFilterChain()->attach(
            $logoFileUploadFilter
        );
    }
}
