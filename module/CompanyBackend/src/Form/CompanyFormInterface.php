<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace CompanyBackend\Form;

use Zend\Form\FormInterface;

/**
 * Interface CompanyFormInterface
 *
 * @package CompanyBackend\Form
 */
interface CompanyFormInterface extends FormInterface
{
    /**
     * @param array $statusOptions
     */
    public function setStatusOptions($statusOptions);

    /**
     * Switch to add mode
     */
    public function addMode();

    /**
     * Switch to edit mode
     */
    public function editMode();
}
