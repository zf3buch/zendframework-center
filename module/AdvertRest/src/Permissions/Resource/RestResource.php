<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertRest\Permissions\Resource;

use Zend\Permissions\Acl\Resource\GenericResource;

/**
 * Class RestResource
 *
 * @package AdvertRest\Permissions\Resource
 */
class RestResource extends GenericResource
{
    /**
     * @const name of resource
     */
    const NAME = 'advert-rest-rest';

    /**
     * RestResource constructor.
     */
    public function __construct()
    {
        parent::__construct(self::NAME);
    }
}
