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
 * Class ShowEditForm
 *
 * @package UserFrontend\View\Helper
 */
class ShowEditForm extends AbstractShowForm
{
    /**
     * Output the edit form
     */
    public function __invoke()
    {
        $this->getUserForm()->editMode();

        return $this->getView()->bootstrapForm($this->getUserForm());
    }
}
