<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserBackend\Permissions\Resource;

use Zend\Permissions\Acl\Resource\GenericResource;

/**
 * Class DisplayResource
 *
 * @package UserBackend\Permissions\Resource
 */
class DisplayResource extends GenericResource
{
    /**
     * @const name of resource
     */
    const NAME = 'user-backend-display';

    /**
     * @const names of privileges
     */
    const PRIVILEGE_INDEX = 'index';
    const PRIVILEGE_SHOW  = 'show';

    /**
     * DisplayResource constructor.
     */
    public function __construct()
    {
        parent::__construct(self::NAME);
    }
}
