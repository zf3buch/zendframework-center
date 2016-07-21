<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserFrontendTest\Controller;

use Application\Test\HttpControllerTestCaseTrait;
use UserFrontend\Controller\IndexController;
use UserModel\Permissions\Role\CompanyRole;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Class IndexControllerTest
 *
 * @package UserFrontendTest\Controller
 */
class IndexControllerTest extends AbstractHttpControllerTestCase
{
    use HttpControllerTestCaseTrait;

    /**
     * @var array
     */
    protected $csvTables = [];

    /**
     * Test index action can be access
     *
     * @param $url
     * @param $locale
     * @param $route
     *
     * @group        controller
     * @group        user-frontend
     * @dataProvider provideIndexActionCanBeAccessed
     */
    public function testIndexActionCanBeAccessed($url, $locale, $route)
    {
        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName($route);
        $this->assertModuleName('userfrontend');
        $this->assertControllerName(IndexController::class);
        $this->assertControllerClass('IndexController');
        $this->assertActionName('index');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            $this->translator->translate(
                'user_frontend_h1_index_index', 'default', $locale
            )
        );

        $this->assertFormElementsExist(
            'user_register_form',
            [
                'csrf', 'email', 'password', 'register_user'
            ]
        );

        $this->assertFormElementsExist(
            'user_login_form',
            [
                'csrf', 'email', 'password', 'login_user'
            ]
        );
    }

    /**
     * Test index action can be access
     *
     * @param $url
     * @param $locale
     * @param $route
     *
     * @group        controller
     * @group        user-frontend
     * @dataProvider provideIndexActionCanBeAccessed
     */
    public function testIndexActionCanBeAccessedLoggedIn(
        $url, $locale, $route
    ) {
        $this->mockLogin(CompanyRole::NAME);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName($route);
        $this->assertModuleName('userfrontend');
        $this->assertControllerName(IndexController::class);
        $this->assertControllerClass('IndexController');
        $this->assertActionName('index');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            $this->translator->translate(
                'user_frontend_h1_index_index', 'default', $locale
            )
        );

        $this->assertFormElementsExist(
            'user_edit_form',
            [
                'csrf', 'email', 'password', 'edit_user'
            ]
        );

        $this->assertFormElementValues(
            'user_edit_form',
            [
                'email' => 'company@zendframework.center',
                'password' => '',
            ]
        );

        $this->assertFormElementsExist(
            'user_logout_form',
            [
                'csrf', 'logout_user'
            ]
        );
    }

    /**
     * @return array
     */
    public function provideIndexActionCanBeAccessed()
    {
        return [
            ['/de/user', 'de_DE', 'user-frontend'],
            ['/en/user', 'en_US', 'user-frontend'],
        ];
    }
}
