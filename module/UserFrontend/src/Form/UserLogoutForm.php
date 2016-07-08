<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontend\Form;

use Zend\Form\Element\Csrf;
use Zend\Form\Element\Submit;
use Zend\Form\Form;

/**
 * Class UserLogoutForm
 *
 * @package UserFrontend\Form
 */
class UserLogoutForm extends Form implements UserLogoutFormInterface
{
    /**
     * Init form
     */
    public function init()
    {
        $this->setAttribute('class', 'form-horizontal');

        $this->add(
            [
                'type' => Csrf::class,
                'name' => 'csrf',
            ]
        );

        $this->add(
            [
                'type'       => Submit::class,
                'name'       => 'logout_user',
                'options'    => [],
                'attributes' => [
                    'id'    => 'logout_user',
                    'class' => 'btn btn-primary',
                    'value' => 'user_frontend_action_logout',
                ],
            ]
        );
    }
}
