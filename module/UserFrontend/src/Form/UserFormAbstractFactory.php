<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontend\Form;

use Interop\Container\ContainerInterface;
use UserModel\Hydrator\UserHydrator;
use UserModel\InputFilter\UserInputFilter;
use Zend\Hydrator\HydratorPluginManager;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterPluginManager;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Class UserFormAbstractFactory
 *
 * @package UserFrontend\Form
 */
class UserFormAbstractFactory implements AbstractFactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     *
     * @return mixed
     */
    public function canCreate(ContainerInterface $container, $requestedName
    ) {
        // TODO: Implement canCreate() method.
    }

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null|null    $options
     *
     * @return mixed
     */
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ) {
        /** @var HydratorPluginManager $hydratorManager */
        $hydratorManager = $container->get('HydratorManager');

        /** @var InputFilterPluginManager $inputFilterManager */
        $inputFilterManager = $container->get('InputFilterManager');

        /** @var UserHydrator $userHydrator */
        $userHydrator = $hydratorManager->get(UserHydrator::class);

        /** @var InputFilterInterface $userInputFilter */
        $userInputFilter = $inputFilterManager->get(
            UserInputFilter::class
        );

        $className = str_replace('Interface', '', $requestedName);

        /** @var AbstractUserForm $form */
        $form = new $className();
        $form->setHydrator($userHydrator);
        $form->setInputFilter($userInputFilter);

        return $form;
    }
}
