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
 * Class DisplayResource
 *
 * @package AdvertFrontend\Permissions\Resource
 */
class DisplayResource extends GenericResource
{
    /**
     * @const name of resource
     */
    const NAME = 'advert-frontend-display';

    /**
     * @const names of privileges
     */
    const PRIVILEGE_INDEX  = 'index';
    const PRIVILEGE_DETAIL = 'details';

    /**
     * DisplayResource constructor.
     */
    public function __construct()
    {
        parent::__construct(self::NAME);
    }
}
