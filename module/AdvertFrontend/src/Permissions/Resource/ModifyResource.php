<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertFrontend\Permissions\Resource;

use Zend\Permissions\Acl\Resource\GenericResource;

/**
 * Class ModifyResource
 *
 * @package AdvertFrontend\Permissions\Resource
 */
class ModifyResource extends GenericResource
{
    /**
     * @const name of resource
     */
    const NAME = 'advert-frontend-modify';

    /**
     * @const names of privileges
     */
    const PRIVILEGE_ADD    = 'add';
    const PRIVILEGE_EDIT   = 'edit';
    const PRIVILEGE_DELETE = 'delete';

    /**
     * ModifyResource constructor.
     */
    public function __construct()
    {
        parent::__construct(self::NAME);
    }
}
