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

        /** @var MethodProphecy $method */
        $method = $events->attach(
            MvcEvent::EVENT_ROUTE,
            [$this->i18nListener, 'redirectHomeRoute'],
            100
        );
        $method->willReturn([$this->i18nListener, 'redirectHomeRoute']);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $events->attach(
            MvcEvent::EVENT_ROUTE,
            [$this->i18nListener, 'setupLocalization'],
            -100
        );
        $method->willReturn([$this->i18nListener, 'redirectHomeRoute']);
        $method->shouldBeCalled();

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

        /** @var MethodProphecy $method */
        $method = $request->getRequestUri();
        $method->willReturn('/whatever');
        $method->shouldBeCalled();

        $response = $this->prophesize(Response::class);

        /** @var MethodProphecy $method */
        $method = $response->setStatusCode();
        $method->shouldNotBeCalled();

        $mvcEvent = $this->prophesize(MvcEvent::class);

        /** @var MethodProphecy $method */
        $method = $mvcEvent->getRequest();
        $method->willReturn($request);
        $method->shouldBeCalled();

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

        /** @var MethodProphecy $method */
        $method = $request->getRequestUri();
        $method->willReturn('/');
        $method->shouldBeCalled();

        $headers = $this->prophesize(Headers::class);

        /** @var MethodProphecy $method */
        $method = $headers->addHeaderLine('Location', '/de');
        $method->shouldBeCalled();

        $response = $this->prophesize(Response::class);

        /** @var MethodProphecy $method */
        $method = $response->getHeaders();
        $method->willReturn($headers);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $response->setStatusCode(Response::STATUS_CODE_301);
        $method->shouldBeCalled();

        $mvcEvent = $this->prophesize(MvcEvent::class);

        /** @var MethodProphecy $method */
        $method = $mvcEvent->getRequest();
        $method->willReturn($request);
        $method->shouldBeCalled();

        /** @var MethodProphecy $method */
        $method = $mvcEvent->getResponse();
        $method->willReturn($response);
        $method->shouldBeCalled();

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
        /** @var MethodProphecy $method */
        $method = $this->translator->setLocale($locale);
        $method->shouldBeCalled();

        $routeMatch = $this->prophesize(RouteMatch::class);

        /** @var MethodProphecy $method */
        $method = $routeMatch->getParam('lang', 'de');
        $method->willReturn($lang);
        $method->shouldBeCalled();

        $mvcEvent = $this->prophesize(MvcEvent::class);

        /** @var MethodProphecy $method */
        $method = $mvcEvent->getRouteMatch();
        $method->willReturn($routeMatch);
        $method->shouldBeCalled();

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
