<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertBackend\Form;

use Zend\Form\FormInterface;

/**
 * Interface AdvertFormInterface
 *
 * @package AdvertBackend\Form
 */
interface AdvertFormInterface extends FormInterface
{
    /**
     * @param array $statusOptions
     */
    public function setStatusOptions($statusOptions);

    /**
     * @param array $typeOptions
     */
    public function setTypeOptions($typeOptions);

    /**
     * @param array $companyOptions
     */
    public function setCompanyOptions($companyOptions);

    /**
     * Switch to edit mode
     */
    public function editMode();
}
