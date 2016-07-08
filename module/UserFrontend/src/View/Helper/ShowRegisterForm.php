<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontend\View\Helper;

/**
 * Class ShowRegisterForm
 *
 * @package UserFrontend\View\Helper
 */
class ShowRegisterForm extends AbstractShowForm
{
    /**
     * Output the register form
     */
    public function __invoke()
    {
        $this->getUserForm()->registerMode();
        $this->getUserForm()->setAttribute(
            'action',
            $this->getView()->url('user-frontend/register', [], true)
        );

        return $this->getView()->bootstrapForm($this->getUserForm());
    }
}
