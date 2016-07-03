<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserModel\Hydrator;

use Zend\Hydrator\ClassMethods;
use Zend\Hydrator\HydratorInterface;
use Zend\Hydrator\Strategy\DateTimeFormatterStrategy;

/**
 * Class UserHydrator
 *
 * @package UserModel\Hydrator
 */
class UserHydrator extends ClassMethods implements HydratorInterface
{
    /**
     * UserHydrator constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->addStrategy(
            'registered',
            new DateTimeFormatterStrategy('Y-m-d H:i:s')
        );
        $this->addStrategy(
            'updated',
            new DateTimeFormatterStrategy('Y-m-d H:i:s')
        );
    }
}
