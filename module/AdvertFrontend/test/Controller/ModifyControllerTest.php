<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/**
 * ZF3 book Zend Framework Center Example AdvertFrontend
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertFrontendTest\Controller;

use AdvertFrontend\Controller\ModifyController;
use Application\Test\HttpControllerTestCaseTrait;
use UserModel\Permissions\Role\CompanyRole;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Class ModifyControllerTest
 *
 * @package AdvertFrontendTest\Controller
 */
class ModifyControllerTest extends AbstractHttpControllerTestCase
{
    use HttpControllerTestCaseTrait;

    /**
     * @var array
     */
    protected $csvTables = [];

    /**
     * Test add action can be access
     *
     * @param $url
     * @param $locale
     * @param $route
     *
     * @group        controller
     * @group        advert-frontend
     * @dataProvider provideAddActionCanBeAccessed
     */
    public function testAddActionCanBeAccessed($url, $locale, $route)
    {
        $this->mockLogin(CompanyRole::NAME);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName($route);
        $this->assertModuleName('advertfrontend');
        $this->assertControllerName(ModifyController::class);
        $this->assertControllerClass('ModifyController');
        $this->assertActionName('add');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            $this->translator->translate(
                'advert_frontend_h1_modify_add', 'default', $locale
            )
        );
    }

    /**
     * @return array
     */
    public function provideAddActionCanBeAccessed()
    {
        return [
            ['/de/job/add', 'de_DE', 'advert-job/modify'],
            ['/en/job/add', 'en_US', 'advert-job/modify'],
        ];
    }

    /**
     * Test edit action can be access
     *
     * @param $url
     * @param $locale
     * @param $route
     *
     * @group        controller
     * @group        advert-frontend
     * @dataProvider provideEditActionCanBeAccessed
     */
    public function testEditActionCanBeAccessed($url, $locale, $route)
    {
        $this->mockLogin(CompanyRole::NAME);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName($route);
        $this->assertModuleName('advertfrontend');
        $this->assertControllerName(ModifyController::class);
        $this->assertControllerClass('ModifyController');
        $this->assertActionName('edit');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            $this->translator->translate(
                'advert_frontend_h1_modify_edit', 'default', $locale
            )
        );
    }

    /**
     * @return array
     */
    public function provideEditActionCanBeAccessed()
    {
        return [
            ['/de/job/edit', 'de_DE', 'advert-job/modify'],
            ['/en/job/edit', 'en_US', 'advert-job/modify'],
        ];
    }

    /**
     * Test delete action can be access
     *
     * @param $url
     * @param $locale
     * @param $route
     *
     * @group        controller
     * @group        advert-frontend
     * @dataProvider provideDeleteActionCanBeAccessed
     */
    public function testDeleteActionCanBeAccessed($url, $locale, $route)
    {
        $this->mockLogin(CompanyRole::NAME);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName($route);
        $this->assertModuleName('advertfrontend');
        $this->assertControllerName(ModifyController::class);
        $this->assertControllerClass('ModifyController');
        $this->assertActionName('delete');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            utf8_encode(
                $this->translator->translate(
                    'advert_frontend_h1_modify_delete', 'default', $locale
                )
            )
        );
    }

    /**
     * @return array
     */
    public function provideDeleteActionCanBeAccessed()
    {
        return [
            ['/de/job/delete', 'de_DE', 'advert-job/modify'],
            ['/en/job/delete', 'en_US', 'advert-job/modify'],
        ];
    }
}
