<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace ApplicationTest\I18n;

use Application\I18n\I18nListener;
use Locale;
use PHPUnit_Framework_TestCase;
use Prophecy\Prophecy\MethodProphecy;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Headers;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;
use Zend\I18n\Translator\Translator;
use Zend\Mvc\MvcEvent;
use Zend\Router\RouteMatch;

/**
 * Class I18nListenerTest
 *
 * @package ApplicationTest\I18n
 */
class I18nListenerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var I18nListener
     */
    private $i18nListener;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * Setup test cases
     */
    protected function setUp()
    {
        $this->translator = $this->prophesize(Translator::class);

        $this->i18nListener = new I18nListener();
        $this->i18nListener->setTranslator($this->translator->reveal());
        $this->i18nListener->setDefaultLang('de');
        $this->i18nListener->setAllowedLocales(
            [
                'de' => 'de_DE',
                'en' => 'en_US',
            ]
        );
    }

    /**
     * Test attaching listeners
     *
     * @group listener
     * @group application
     */
    public function testAttach()
    {
        $events = $this->prophesize(EventManagerInterface::class);

        $events->attach(
            MvcEvent::EVENT_ROUTE,
            [$this->i18nListener, 'redirectHomeRoute'],
            100
        )->willReturn([$this->i18nListener, 'redirectHomeRoute'])
            ->shouldBeCalled();

        $events->attach(
            MvcEvent::EVENT_ROUTE,
            [$this->i18nListener, 'setupLocalization'],
            -100
        )->willReturn([$this->i18nListener, 'setupLocalization'])
            ->shouldBeCalled();

        $this->i18nListener->attach($events->reveal());
    }

    /**
     * Test redirect to home route with request uri /whatever
     *
     * @group listener
     * @group application
     */
    public function testRedirectHomeRouteWhatever()
    {
        $request = $this->prophesize(Request::class);
        $request->getRequestUri()
            ->willReturn('/whatever')
            ->shouldBeCalled();

        $response = $this->prophesize(Response::class);
        $response->setStatusCode()->shouldNotBeCalled();

        $mvcEvent = $this->prophesize(MvcEvent::class);
        $mvcEvent->getRequest()
            ->willReturn($request)->shouldBeCalled();

        $this->i18nListener->redirectHomeRoute($mvcEvent->reveal());
    }

    /**
     * Test redirect to home route with request uri /
     *
     * @group listener
     * @group application
     */
    public function testRedirectHomeRouteRoot()
    {
        $request = $this->prophesize(Request::class);
        $request->getRequestUri()->willReturn('/')->shouldBeCalled();

        $headers = $this->prophesize(Headers::class);
        $headers->addHeaderLine('Location', '/de')->shouldBeCalled();

        $response = $this->prophesize(Response::class);
        $response->getHeaders()->willReturn($headers)->shouldBeCalled();
        $response->setStatusCode(Response::STATUS_CODE_301)
            ->shouldBeCalled();

        $mvcEvent = $this->prophesize(MvcEvent::class);
        $mvcEvent->getRequest()->willReturn($request)->shouldBeCalled();
        $mvcEvent->getResponse()->willReturn($response)->shouldBeCalled();

        $this->i18nListener->redirectHomeRoute($mvcEvent->reveal());
    }

    /**
     * Test setup the localization
     *
     * @param string $locale
     * @param string $lang
     *
     * @group listener
     * @group application
     * @dataProvider provideSetupLocalization
     */
    public function testSetupLocalization($locale, $lang)
    {
        $this->translator->setLocale($locale)->shouldBeCalled();

        $routeMatch = $this->prophesize(RouteMatch::class);
        $routeMatch->getParam('lang', 'de')
            ->willReturn($lang)
            ->shouldBeCalled();

        $mvcEvent = $this->prophesize(MvcEvent::class);
        $mvcEvent->getRouteMatch()
            ->willReturn($routeMatch)
            ->shouldBeCalled();

        $this->i18nListener->setupLocalization($mvcEvent->reveal());

        $this->assertEquals($locale, Locale::getDefault());
    }

    /**
     * Data provider for setup the localization
     *
     * @return array
     */
    public function provideSetupLocalization()
    {
        return [
            ['de_DE', 'de'],
            ['en_US', 'en'],
        ];
    }
}
