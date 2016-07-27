<?php
/**
 * ZF3 book Zend Framework Center Example AdvertFrontend
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertFrontendTest\Controller;

use AdvertFrontend\Controller\DisplayController;
use Application\Test\HttpControllerTestCaseTrait;
use Zend\Db\Sql\Sql;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Class DisplayControllerTest
 *
 * @package AdvertFrontendTest\Controller
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
     * @group        advert-frontend
     * @dataProvider provideIndexActionCanBeAccessed
     */
    public function testIndexActionCanBeAccessed($url, $locale, $route, $h1
    ) {
        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName($route);
        $this->assertModuleName('advertfrontend');
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
                '/de/job', 'de_DE', 'advert-job',
                'advert_frontend_h1_display_jobs'
            ],
            [
                '/en/job', 'en_US', 'advert-job',
                'advert_frontend_h1_display_jobs'
            ],
            [
                '/de/project', 'de_DE', 'advert-project',
                'advert_frontend_h1_display_projects'
            ],
            [
                '/en/project', 'en_US', 'advert-project',
                'advert_frontend_h1_display_projects'
            ],
        ];
    }

    /**
     * Test index action output
     *
     * @param $type
     * @param $page
     *
     * @group        controller
     * @group        advert-frontend
     * @dataProvider provideIndexActionAdvertOutput
     */
    public function testIndexActionAdvertOutput($type, $page, $class)
    {
        $url = $page == 1 ? '/de/' . $type : '/de/' . $type . '/' . $page;

        $this->dispatch($url, 'GET');

        $queryAdvert = $this->getConnection()->createQueryTable(
            'fetchAdvertsByPage',
            $this->generateQueryAdvertsByPage($type, $page)
        );

        for ($count = 0; $count < $queryAdvert->getRowCount(); $count++) {
            $row = $queryAdvert->getRow($count);

            $this->assertQueryContentRegex(
                '.' . $class . ' .panel-heading .left-text strong a',
                '#' . preg_quote($row['title']) . '#'
            );
            $this->assertQueryContentRegex(
                '.' . $class . ' .panel-heading .right-text',
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
            ['job', 1, 'panel-primary'],
            ['job', 2, 'panel-primary'],
            ['job', 3, 'panel-primary'],
            ['project', 1, 'panel-success'],
            ['project', 2, 'panel-success'],
            ['project', 3, 'panel-success'],
        ];
    }

    /**
     * Test detail action can be accessed
     *
     * @param $id
     *
     * @group        controller
     * @group        advert-frontend
     * @dataProvider provideDetailActionCanBeAccessed
     */
    public function testDetailActionCanBeAccessed($id)
    {
        $queryAdvert = $this->getConnection()->createQueryTable(
            'fetchAdvertsByPage',
            $this->generateQueryAdvertById($id)
        );

        $row = $queryAdvert->getRow(0);

        $url = '/de/' . $row['type'] . '/detail/' . $id;

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName(
            'advert-' . $row['type'] . '/detail'
        );
        $this->assertModuleName('advertfrontend');
        $this->assertControllerName(DisplayController::class);
        $this->assertControllerClass('DisplayController');
        $this->assertActionName('detail');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            utf8_encode($row['title'])
        );
    }

    /**
     * @return array
     */
    public function provideDetailActionCanBeAccessed()
    {
        return [
            [1], [3], [7], [10], [15], [18],
        ];
    }

    /**
     * Test detail action is redirected
     *
     * @param $url
     * @param $redirect
     *
     * @group        controller
     * @group        advert-frontend
     * @dataProvider provideDetailActionIsRedirected
     */
    public function testDetailActionIsRedirected($url, $redirect)
    {
        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertRedirect();
        $this->assertRedirectTo($redirect);
    }

    /**
     * @return array
     */
    public function provideDetailActionIsRedirected()
    {
        return [
            ['/de/job/detail', '/de/job'],
            ['/de/job/detail/17', '/de/job'],
            ['/de/project/detail/19', '/de/project'],
            ['/de/job/detail/20', '/de/job'],
        ];
    }

    /**
     * @param string $type
     * @param int    $page
     *
     * @return string
     */
    protected function generateQueryAdvertsByPage($type = 'job', $page = 1)
    {
        $limit  = 5;
        $offset = ($page - 1) * $limit;

        $sql = new Sql($this->adapter);

        $select = $sql->select('advert');
        $select->limit($limit);
        $select->offset($offset);
        $select->order(['advert.created' => 'DESC']);
        $select->where->equalTo('advert.status', 'approved');
        $select->where->equalTo('advert.type', $type);

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
        $select->where->equalTo('advert.status', 'approved');
        $select->where->equalTo('advert.id', $id);

        $this->addCompanyJoinToQuery($select);

        return $sql->buildSqlString($select);
    }
}
