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
use PHPUnit_Extensions_Database_DataSet_CsvDataSet;
use PHPUnit_Extensions_Database_DataSet_IDataSet;
use PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection;
use PHPUnit_Extensions_Database_DB_IDatabaseConnection;
use PHPUnit_Extensions_Database_TestCase_Trait;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Class DisplayControllerTest
 *
 * @package AdvertFrontendTest\Controller
 */
class DisplayControllerTest extends AbstractHttpControllerTestCase
{
    use PHPUnit_Extensions_Database_TestCase_Trait;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var Adapter
     */
    private $adapter = null;

    /**
     * @var PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection
     */
    private $connection = null;

    /**
     * Returns the test database connection.
     *
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    protected function getConnection()
    {
        if (!$this->connection) {
            $this->connection = $this->createDefaultDBConnection(
                $this->adapter->getDriver()->getConnection()->getResource(
                ),
                'zf-center-test'
            );
        }

        return $this->connection;
    }

    /**
     * Returns the test dataset.
     *
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    protected function getDataSet()
    {
        $dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet();
        $dataSet->addTable(
            'company',
            PROJECT_ROOT . "/data/test-data/company.test-data.csv"
        );
        $dataSet->addTable(
            'advert',
            PROJECT_ROOT . "/data/test-data/advert.test-data.csv"
        );

        return $dataSet;
    }

    /**
     * Setup test cases
     */
    protected function setUp()
    {
        parent::setUp();

        $this->setApplicationConfig(
            include PROJECT_ROOT . '/config/test.config.php'
        );

        $this->translator = $this->getApplicationServiceLocator()->get(
            TranslatorInterface::class
        );

        $this->adapter = $this->getApplicationServiceLocator()->get(
            Adapter::class
        );

        $this->databaseTester = null;

        $this->getDatabaseTester()->setSetUpOperation(
            $this->getSetUpOperation()
        );
        $this->getDatabaseTester()->setDataSet($this->getDataSet());
        $this->getDatabaseTester()->onSetUp();
    }

    /**
     * Test index action can be accessed
     *
     * @param $url
     * @param $locale
     * @param $route
     * @param $h1
     *
     * @group        controller
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
            [1], [2], [3], [4], [5], [6], [7], [8], [9], [10],
            [11], [12], [13], [14], [15], [16], [18],
        ];
    }

    /**
     * Test detail action is redirected
     *
     * @param $url
     * @param $redirect
     *
     * @group        controller
     * @dataProvider provideDetailActionIsRedirected
     */
    public function testDetailActionIsRedirected($url, $redirect)
    {
        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(302);
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
    private function generateQueryAdvertsByPage($type = 'job', $page = 1)
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
        $select->join(
            'company',
            'advert.company = company.id',
            [
                'company_id'         => 'id',
                'company_registered' => 'registered',
                'company_updated'    => 'updated',
                'company_status'     => 'status',
                'company_name'       => 'name',
                'company_email'      => 'email',
                'company_contact'    => 'contact',
                'company_logo'       => 'logo',
            ]
        );

        return $sql->buildSqlString($select);
    }

    /**
     * @param int $id
     *
     * @return string
     */
    private function generateQueryAdvertById($id)
    {
        $sql = new Sql($this->adapter);

        $select = $sql->select('advert');
        $select->where->equalTo('advert.status', 'approved');
        $select->where->equalTo('advert.id', $id);
        $select->join(
            'company',
            'advert.company = company.id',
            [
                'company_id'         => 'id',
                'company_registered' => 'registered',
                'company_updated'    => 'updated',
                'company_status'     => 'status',
                'company_name'       => 'name',
                'company_email'      => 'email',
                'company_contact'    => 'contact',
                'company_logo'       => 'logo',
            ]
        );

        return $sql->buildSqlString($select);
    }
}
