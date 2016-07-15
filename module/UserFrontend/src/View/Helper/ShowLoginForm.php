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
 * Class ShowLoginForm
 *
 * @package UserFrontend\View\Helper
 */
class ShowLoginForm extends AbstractShowForm
{
    /**
     * Output the login form
     *
     * @param string $formClass
     *
     * @return
     */
    public function __invoke($formClass = 'form-horizontal')
    {
        return $this->getView()->bootstrapForm(
            $this->getUserForm(), [], $formClass
        );
    }
}
