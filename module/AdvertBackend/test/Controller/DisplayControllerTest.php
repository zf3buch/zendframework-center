<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertBackendTest\Controller;

use AdvertBackend\Controller\DisplayController;
use Application\Test\HttpControllerTestCaseTrait;
use UserModel\Permissions\Role\AdminRole;
use Zend\Db\Sql\Sql;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Class DisplayControllerTest
 *
 * @package AdvertBackendTest\Controller
 */
class DisplayControllerTest extends AbstractHttpControllerTestCase
{
    use HttpControllerTestCaseTrait;

    /**
     * @var array
     */
    protected $csvTables
        = [
            'company' => PROJECT_ROOT
                . "/data/test-data/company.test-data.csv",
            'advert'  => PROJECT_ROOT
                . "/data/test-data/advert.test-data.csv",
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
     * @group        advert-backend
     * @dataProvider provideIndexActionCanBeAccessed
     */
    public function testIndexActionCanBeAccessed($url, $locale, $route, $h1
    ) {
        $this->mockLogin(AdminRole::NAME);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName($route);
        $this->assertModuleName('advertbackend');
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
                '/de/advert-backend', 'de_DE', 'advert-backend',
                'advert_backend_h1_display_index'
            ],
            [
                '/en/advert-backend', 'en_US', 'advert-backend',
                'advert_backend_h1_display_index'
            ],
        ];
    }

    /**
     * Test index action output
     *
     * @param $page
     *
     * @group        controller
     * @group        advert-backend
     * @dataProvider provideIndexActionAdvertOutput
     */
    public function testIndexActionAdvertOutput($page)
    {
        $this->mockLogin(AdminRole::NAME);

        $url = $page == 1
            ? '/de/advert-backend'
            : '/de/advert-backend/' . $page;

        $this->dispatch($url, 'GET');

        $queryAdvert = $this->getConnection()->createQueryTable(
            'fetchAdvertsByPage',
            $this->generateQueryAdvertsByPage($page)
        );

        for ($count = 0; $count < $queryAdvert->getRowCount(); $count++) {
            $row = $queryAdvert->getRow($count);

            $this->assertQueryContentRegex(
                'table tbody tr td a',
                '#' . preg_quote($row['title']) . '#'
            );
            $this->assertQueryContentRegex(
                'table tbody tr td',
                '#' . preg_quote($row['company_name']) . '#'
            );
        }
    }

    /**
     * @return array
     */
    public function provideIndexActionAdvertOutput()
    {
        return [
            [1],
            [2],
        ];
    }

    /**
     * Test detail action can be accessed
     *
     * @param $id
     *
     * @group        controller
     * @group        advert-backend
     * @dataProvider provideDetailActionCanBeAccessed
     */
    public function testDetailActionCanBeAccessed($id)
    {
        $this->mockLogin(AdminRole::NAME);

        $queryAdvert = $this->getConnection()->createQueryTable(
            'fetchAdvertsByPage',
            $this->generateQueryAdvertById($id)
        );

        $row = $queryAdvert->getRow(0);

        $url = '/de/advert-backend/show/' . $id;

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName(
            'advert-backend/show'
        );
        $this->assertModuleName('advertbackend');
        $this->assertControllerName(DisplayController::class);
        $this->assertControllerClass('DisplayController');
        $this->assertActionName('show');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            $this->translator->translate('advert_backend_h1_display_show')
        );
    }

    /**
     * @return array
     */
    public function provideDetailActionCanBeAccessed()
    {
        return [
            [1], [3], [15], [17], [18], [19],
        ];
    }

    /**
     * @param int $page
     *
     * @return string
     */
    protected function generateQueryAdvertsByPage($page = 1)
    {
        $limit  = 15;
        $offset = ($page - 1) * $limit;

        $sql = new Sql($this->adapter);

        $select = $sql->select('advert');
        $select->limit($limit);
        $select->offset($offset);
        $select->order(['advert.created' => 'DESC']);

        $this->addCompanyJoinToQuery($select);

        return $sql->buildSqlString($select);
    }

    /**
     * @param int $id
     *
     * @return string
     */
    protected function generateQueryAdvertById($id)
    {
        $sql = new Sql($this->adapter);

        $select = $sql->select('advert');
        $select->where->equalTo('advert.id', $id);

        $this->addCompanyJoinToQuery($select);

        return $sql->buildSqlString($select);
    }
}
