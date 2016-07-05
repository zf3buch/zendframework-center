<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserModel\Permissions\Role;

use Zend\Permissions\Acl\Role\GenericRole;

/**
 * Class CompanyRole
 *
 * @package UserModel\Permissions\Role
 */
class CompanyRole extends GenericRole
{
    /**
     * @var string name of role
     */
    const NAME = 'company';

    /**
     * CompanyRole constructor.
     */
    public function __construct()
    {
        parent::__construct( self::NAME);
    }
}
