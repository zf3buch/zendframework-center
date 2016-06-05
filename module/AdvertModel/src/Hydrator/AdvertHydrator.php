<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertModel\Hydrator;

use Zend\Hydrator\ClassMethods;
use Zend\Hydrator\HydratorInterface;
use Zend\Hydrator\Strategy\DateTimeFormatterStrategy;

/**
 * Class AdvertHydrator
 *
 * @package AdvertModel\Hydrator
 */
class AdvertHydrator extends ClassMethods implements HydratorInterface
{
    /**
     * CompanyHydrator constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->addStrategy(
            'created',
            new DateTimeFormatterStrategy('Y-m-d H:i:s')
        );
        $this->addStrategy(
            'updated',
            new DateTimeFormatterStrategy('Y-m-d H:i:s')
        );
    }
}
