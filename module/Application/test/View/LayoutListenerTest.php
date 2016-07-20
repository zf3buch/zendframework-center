<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace ApplicationTest\View;

use Application\View\LayoutListener;
use PHPUnit_Framework_TestCase;
use Prophecy\Prophecy\MethodProphecy;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\View\Resolver\ResolverInterface;

/**
 * Class LayoutListenerTest
 *
 * @package ApplicationTest\I18n
 */
class LayoutListenerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test attaching listeners
     *
     * @group listener
     * @group application
     */
    public function testAttach()
    {
        $layoutSegments = ['header', 'footer'];

        $layoutListener = new LayoutListener($layoutSegments);

        $events = $this->prophesize(EventManagerInterface::class);
        $events->attach(
            MvcEvent::EVENT_RENDER,
            [$layoutListener, 'renderLayoutSegments'],
            -100
        )->willReturn(
            [$layoutListener, 'renderLayoutSegments']
        )->shouldBeCalled();

        $layoutListener->attach($events->reveal());
    }

    /**
     * Test render layout segments json
     *
     * @group listener
     * @group application
     */
    public function testRenderLayoutSegmentsJson()
    {
        $layoutSegments = ['header', 'footer'];

        $layoutListener = new LayoutListener($layoutSegments);

        $viewModel = new JsonModel();

        $mvcEvent = $this->prophesize(MvcEvent::class);
        $mvcEvent->getViewModel()
            ->willReturn($viewModel)
            ->shouldBeCalled();

        $layoutListener->renderLayoutSegments($mvcEvent->reveal());

        $expectedChildren = [];

        $this->assertEquals($expectedChildren, $viewModel->getChildren());
    }

    /**
     * Test render layout segments existing segments
     *
     * @group listener
     * @group application
     */
    public function testRenderLayoutSegmentsExistingSegments()
    {
        $layoutSegments = ['header', 'footer'];

        $layoutListener = new LayoutListener($layoutSegments);

        $resolver = $this->prophesize(ResolverInterface::class);
        $resolver->resolve('layout/header')
            ->willReturn(true)
            ->shouldBeCalled();
        $resolver->resolve('layout/footer')
            ->willReturn(true)
            ->shouldBeCalled();

        $serviceManager = $this->prophesize(ServiceManager::class);;
        $serviceManager->get('ViewResolver')
            ->willReturn($resolver->reveal())
            ->shouldBeCalled();

        $application = $this->prophesize(Application::class);;
        $application->getServiceManager()
            ->willReturn($serviceManager->reveal())
            ->shouldBeCalled();

        $viewModel = new ViewModel();

        $mvcEvent = $this->prophesize(MvcEvent::class);
        $mvcEvent->getViewModel()
            ->willReturn($viewModel)
            ->shouldBeCalled();
        $mvcEvent->getApplication()
            ->willReturn($application)
            ->shouldBeCalled();

        $layoutListener->renderLayoutSegments($mvcEvent->reveal());

        $headerViewModel = new ViewModel();
        $headerViewModel->setTemplate('layout/header');
        $headerViewModel->setCaptureTo('header');

        $footerViewModel = new ViewModel();
        $footerViewModel->setTemplate('layout/footer');
        $footerViewModel->setCaptureTo('footer');

        $expectedChildren = [
            $headerViewModel,
            $footerViewModel,
        ];

        $this->assertEquals($expectedChildren, $viewModel->getChildren());
    }

    /**
     * Test render layout segments wrong segments
     *
     * @group listener
     * @group application
     */
    public function testRenderLayoutSegmentsWrongSegment()
    {
        $layoutSegments = ['header', 'footer', 'sidebar'];

        $layoutListener = new LayoutListener($layoutSegments);

        $resolver = $this->prophesize(ResolverInterface::class);
        $resolver->resolve('layout/header')
            ->willReturn(true)
            ->shouldBeCalled();
        $resolver->resolve('layout/footer')
            ->willReturn(true)
            ->shouldBeCalled();
        $resolver->resolve('layout/sidebar')
            ->willReturn(false)
            ->shouldBeCalled();

        $serviceManager = $this->prophesize(ServiceManager::class);;
        $serviceManager->get('ViewResolver')
            ->willReturn($resolver->reveal())
            ->shouldBeCalled();

        $application = $this->prophesize(Application::class);;
        $application->getServiceManager()
            ->willReturn($serviceManager->reveal())
            ->shouldBeCalled();

        $viewModel = new ViewModel();

        $mvcEvent = $this->prophesize(MvcEvent::class);
        $mvcEvent->getViewModel()
            ->willReturn($viewModel)
            ->shouldBeCalled();
        $mvcEvent->getApplication()
            ->willReturn($application)
            ->shouldBeCalled();

        $layoutListener->renderLayoutSegments($mvcEvent->reveal());

        $headerViewModel = new ViewModel();
        $headerViewModel->setTemplate('layout/header');
        $headerViewModel->setCaptureTo('header');

        $footerViewModel = new ViewModel();
        $footerViewModel->setTemplate('layout/footer');
        $footerViewModel->setCaptureTo('footer');

        $expectedChildren = [
            $headerViewModel,
            $footerViewModel,
        ];

        $this->assertEquals($expectedChildren, $viewModel->getChildren());
    }
}

