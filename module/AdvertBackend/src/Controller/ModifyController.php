<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertBackend\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class ModifyController
 *
 * @package AdvertBackend\Controller
 */
class ModifyController extends AbstractActionController
{
    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        $viewModel = new ViewModel();

        return $viewModel;
    }
}
