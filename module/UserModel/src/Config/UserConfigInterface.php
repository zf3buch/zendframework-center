<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserModel\Config;

/**
 * Interface UserConfigInterface
 *
 * @package UserModel\Config
 */
interface UserConfigInterface
{
    /**
     * Get the status options
     */
    public function getStatusOptions();
    
    /**
     * Get the role options
     */
    public function getRoleOptions();
}
