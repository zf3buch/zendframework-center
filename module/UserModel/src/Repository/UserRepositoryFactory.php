<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserModel\Repository;

use UserModel\Storage\Db\UserDbStorage;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UserRepositoryFactory
 *
 * @package UserModel\Repository
 */
class UserRepositoryFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null|null    $options
     *
     * @return mixed
     */
    public function __invoke(
        ContainerInterface $container, $requestedName,
        array $options = null
    ) {
        $userDbStorage = $container->get(UserDbStorage::class);

        return new UserRepository($userDbStorage);
    }
}
