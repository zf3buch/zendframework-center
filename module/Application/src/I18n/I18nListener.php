<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Application\I18n;

use Locale;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;
use Zend\I18n\Translator\Translator;
use Zend\Mvc\MvcEvent;

/**
 * Class I18nListener
 *
 * @package Application\I18n
 */
class I18nListener extends AbstractListenerAggregate
{
    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var string
     */
    private $defaultLang;

    /**
     * @var array
     */
    private $allowedLocales;

    /**
     * @param Translator $translator
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param string $defaultLang
     */
    public function setDefaultLang($defaultLang)
    {
        $this->defaultLang = $defaultLang;
    }

    /**
     * @param array $allowedLocales
     */
    public function setAllowedLocales($allowedLocales)
    {
        $this->allowedLocales = $allowedLocales;
    }

    /**
     * Attach listener to setup locale
     *
     * @param EventManagerInterface $events
     * @param int                   $priority
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_ROUTE,
            [$this, 'redirectHomeRoute'],
            100
        );

        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_ROUTE,
            [$this, 'setupLocalization'],
            -100
        );
    }

    /**
     * Listen to the "route" event and do a redirection for home route
     *
     * @param  MvcEvent $e
     *
     * @return null
     */
    public function redirectHomeRoute(MvcEvent $e)
    {
        /** @var Request $request */
        $request = $e->getRequest();

        // redirect to default language url
        if ($request->getRequestUri() == '/') {
            $url = '/' . $this->defaultLang;

            /** @var Response $response */
            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(Response::STATUS_CODE_301);

            return $response;
        }
    }

    /**
     * Listen to the "route" event and setup the localization
     *
     * @param  MvcEvent $e
     *
     * @return null
     */
    public function setupLocalization(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();

        $lang   = $routeMatch->getParam('lang', $this->defaultLang);
        $locale = $this->allowedLocales[$lang];

        Locale::setDefault($locale);

        $this->translator->setLocale($locale);
    }
}
