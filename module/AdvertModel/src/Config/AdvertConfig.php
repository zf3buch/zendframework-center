<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertModel\Config;

use Zend\Config\Config;

/**
 * Class AdvertConfig
 *
 * @package AdvertModel\Config
 */
class AdvertConfig extends Config implements AdvertConfigInterface
{
    /**
     * Get the status options
     */
    public function getStatusOptions()
    {
        return $this->get('status_options')->toArray();
    }

    /**
     * Get the type options
     */
    public function getTypeOptions()
    {
        return $this->get('type_options')->toArray();
    }
}
