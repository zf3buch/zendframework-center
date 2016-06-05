<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertModel\InputFilter;

/**
 * Interface AdvertInputFilterInterface
 *
 * @package AdvertModel\InputFilter
 */
interface AdvertInputFilterInterface
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
     * Init input filter
     */
    public function init();
}