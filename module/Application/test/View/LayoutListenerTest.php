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
     */
    public function testAttach()
    {
        $layoutSegments = ['header', 'footer'];

        $layoutListener = new LayoutListener($layoutSegments);

        $events = $this->prophesize(EventManagerInterface::class);

        /** @var MethodProphecy $method */
        $method = $events->attach(
            MvcEvent::EVENT_RENDER,
            [$layoutListener, 'renderLayoutSegments'],
            -100
        );
        $method->willReturn(
            [$layoutListener, 'renderLayoutSegments']
        );
        $method->shouldBeCalled();

        $layoutListener->attach($events->reveal());
    }

    /**
     * Test render layout segments json
     *
     * @group listener
     */
    public function testRenderLayoutSegmentsJson()
    {
        $layoutSegments = ['header', 'footer'];

        $layoutListener = new LayoutListener($layoutSegments);

        $viewModel = new JsonModel();

        $mvcEvent = $this->prophesize(MvcEvent::class);

        /** @var MethodProphecy $method */
        $method = $mvcEvent->getViewModel();
        $method->willReturn($viewModel);
        $method->shouldBeCalled();

        $layoutListener->renderLayoutSegments($mvcEvent->reveal());

        $expectedChildren = [];

        $this->assertEquals($expectedChildren, $viewModel->getChildren());
    }

    /**
     * Test render layout segments existing segments
     *
     * @group listener
     */
    public function testRenderLayoutSegmentsExistingSegments()
    {
        $layoutSegments = ['header', 'footer'];

        $layoutListener = new LayoutListener($layoutSegments);

        $resolver = $this->prophesize(ResolverInterface::class);

        /** @var MethodProphecy $method */
        $method = $resolver->resolve('layout/header');
        $method->willReturn(true);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $resolver->resolve('layout/footer');
        $method->willReturn(true);
        $method->shouldBeCalled();

        $serviceManager = $this->prophesize(ServiceManager::class);;

        $method = $serviceManager->get('ViewResolver');
        $method->willReturn($resolver->reveal());
        $method->shouldBeCalled();

        $application = $this->prophesize(Application::class);;

        $method = $application->getServiceManager();
        $method->willReturn($serviceManager->reveal());
        $method->shouldBeCalled();

        $viewModel = new ViewModel();

        $mvcEvent = $this->prophesize(MvcEvent::class);

        /** @var MethodProphecy $method */
        $method = $mvcEvent->getViewModel();
        $method->willReturn($viewModel);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $mvcEvent->getApplication();
        $method->willReturn($application);
        $method->shouldBeCalled();

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
     */
    public function testRenderLayoutSegmentsWrongSegment()
    {
        $layoutSegments = ['header', 'footer', 'sidebar'];

        $layoutListener = new LayoutListener($layoutSegments);

        $resolver = $this->prophesize(ResolverInterface::class);

        /** @var MethodProphecy $method */
        $method = $resolver->resolve('layout/header');
        $method->willReturn(true);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $resolver->resolve('layout/footer');
        $method->willReturn(true);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $resolver->resolve('layout/sidebar');
        $method->willReturn(false);
        $method->shouldBeCalled();

        $serviceManager = $this->prophesize(ServiceManager::class);;

        $method = $serviceManager->get('ViewResolver');
        $method->willReturn($resolver->reveal());
        $method->shouldBeCalled();

        $application = $this->prophesize(Application::class);;

        $method = $application->getServiceManager();
        $method->willReturn($serviceManager->reveal());
        $method->shouldBeCalled();

        $viewModel = new ViewModel();

        $mvcEvent = $this->prophesize(MvcEvent::class);

        /** @var MethodProphecy $method */
        $method = $mvcEvent->getViewModel();
        $method->willReturn($viewModel);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $mvcEvent->getApplication();
        $method->willReturn($application);
        $method->shouldBeCalled();

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

