<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserBackendTest\Controller;

use UserBackend\Controller\DisplayController;
use Application\Test\HttpControllerTestCaseTrait;
use UserModel\Permissions\Role\AdminRole;
use Zend\Db\Sql\Sql;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Class DisplayControllerTest
 *
 * @package UserBackendTest\Controller
 */
class DisplayControllerTest extends AbstractHttpControllerTestCase
{
    use HttpControllerTestCaseTrait;

    /**
     * @var array
     */
    protected $csvTables
        = [
            'user' => PROJECT_ROOT
                . "/data/test-data/user.test-data.csv",
        ];

    /**
     * Test index action can be accessed
     *
     * @param $url
     * @param $locale
     * @param $route
     * @param $h1
     *
     * @group        controller
     * @group        user-backend
     * @dataProvider provideIndexActionCanBeAccessed
     */
    public function testIndexActionCanBeAccessed($url, $locale, $route, $h1
    ) {
        $this->mockLogin(AdminRole::NAME);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName($route);
        $this->assertModuleName('userbackend');
        $this->assertControllerName(DisplayController::class);
        $this->assertControllerClass('DisplayController');
        $this->assertActionName('index');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            $this->translator->translate($h1, 'default', $locale)
        );
    }

    /**
     * @return array
     */
    public function provideIndexActionCanBeAccessed()
    {
        return [
            [
                '/de/user-backend', 'de_DE', 'user-backend',
                'user_backend_h1_display_index'
            ],
            [
                '/en/user-backend', 'en_US', 'user-backend',
                'user_backend_h1_display_index'
            ],
        ];
    }

    /**
     * Test index action output
     *
     * @group        controller
     * @group        user-backend
     */
    public function testIndexActionUserOutput()
    {
        $this->mockLogin(AdminRole::NAME);

        $page = 1;
        $url = '/de/user-backend';

        $this->dispatch($url, 'GET');

        $queryUser = $this->getConnection()->createQueryTable(
            'fetchUsersByPage',
            $this->generateQueryUsersByPage($page)
        );

        for ($count = 0; $count < $queryUser->getRowCount(); $count++) {
            $row = $queryUser->getRow($count);

            $this->assertQueryContentRegex(
                'table tbody tr td',
                '#' . preg_quote($row['email']) . '#'
            );
        }
    }

    /**
     * Test detail action can be accessed
     *
     * @param $id
     *
     * @group        controller
     * @group        user-backend
     * @dataProvider provideDetailActionCanBeAccessed
     */
    public function testDetailActionCanBeAccessed($id)
    {
        $this->mockLogin(AdminRole::NAME);

        $queryUser = $this->getConnection()->createQueryTable(
            'fetchUsersByPage',
            $this->generateQueryUserById($id)
        );

        $row = $queryUser->getRow(0);

        $url = '/de/user-backend/show/' . $id;

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName(
            'user-backend/show'
        );
        $this->assertModuleName('userbackend');
        $this->assertControllerName(DisplayController::class);
        $this->assertControllerClass('DisplayController');
        $this->assertActionName('show');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            $this->translator->translate('user_backend_h1_display_show')
        );
    }

    /**
     * @return array
     */
    public function provideDetailActionCanBeAccessed()
    {
        return [
            [1], [2],
        ];
    }

    /**
     * @param int $page
     *
     * @return string
     */
    protected function generateQueryUsersByPage($page = 1)
    {
        $limit  = 15;
        $offset = ($page - 1) * $limit;

        $sql = new Sql($this->adapter);

        $select = $sql->select('user');
        $select->limit($limit);
        $select->offset($offset);
        $select->order(['user.registered' => 'DESC']);

        return $sql->buildSqlString($select);
    }

    /**
     * @param int $id
     *
     * @return string
     */
    protected function generateQueryUserById($id)
    {
        $sql = new Sql($this->adapter);

        $select = $sql->select('user');
        $select->where->equalTo('user.id', $id);

        return $sql->buildSqlString($select);
    }
}
