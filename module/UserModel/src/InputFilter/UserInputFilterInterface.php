<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserModel\InputFilter;

/**
 * Interface UserInputFilterInterface
 *
 * @package UserModel\InputFilter
 */
interface UserInputFilterInterface
{
    /**
     * @param array $statusOptions
     */
    public function setStatusOptions($statusOptions);

    /**
     * @param array $roleOptions
     */
    public function setRoleOptions($roleOptions);

    /**
     * Init input filter
     */
    public function init();
}
