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
     * Test index action jobs with de lang
     */
    public function testIndexActionJobsWithDeLangCanBeAccessed()
    {
        $this->dispatch('/de/job', 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName('advert-job');
        $this->assertModuleName('advertfrontend');
        $this->assertControllerName(DisplayController::class);
        $this->assertControllerClass('DisplayController');
        $this->assertActionName('index');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            $this->translator->translate(
                'advert_frontend_h1_display_jobs', 'default', 'de_DE'
            )
        );
    }

    /**
     * Test index action jobs with en lang
     */
    public function testIndexActionJobsWithEnLangCanBeAccessed()
    {
        $this->dispatch('/en/job', 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName('advert-job');
        $this->assertModuleName('advertfrontend');
        $this->assertControllerName(DisplayController::class);
        $this->assertControllerClass('DisplayController');
        $this->assertActionName('index');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            $this->translator->translate(
                'advert_frontend_h1_display_jobs', 'default', 'en_US'
            )
        );
    }

    /**
     * Test index action projects with de lang
     */
    public function testIndexActionProjectsWithDeLangCanBeAccessed()
    {
        $this->dispatch('/de/job', 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName('advert-job');
        $this->assertModuleName('advertfrontend');
        $this->assertControllerName(DisplayController::class);
        $this->assertControllerClass('DisplayController');
        $this->assertActionName('index');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            $this->translator->translate(
                'advert_frontend_h1_display_jobs', 'default', 'de_DE'
            )
        );
    }

    /**
     * Test index action projects with en lang
     */
    public function testIndexActionProjectsWithEnLangCanBeAccessed()
    {
        $this->dispatch('/en/job', 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName('advert-job');
        $this->assertModuleName('advertfrontend');
        $this->assertControllerName(DisplayController::class);
        $this->assertControllerClass('DisplayController');
        $this->assertActionName('index');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            $this->translator->translate(
                'advert_frontend_h1_display_jobs', 'default', 'en_US'
            )
        );
    }

    /**
     * Test index action output
     */
    public function testIndexActionJobOutput()
    {
        $type = 'job';
        $page = 1;

        $this->dispatch('/de/job', 'GET');

        $queryJob = $this->getConnection()->createQueryTable(
            'fetchJob',
            $this->generateQueryAdvertsByPage($type, $page)
        );

        for ($count = 0; $count < $queryJob->getRowCount(); $count++) {
            $row = $queryJob->getRow($count);

            $this->assertQueryContentRegex(
                '.panel-primary .panel-heading .left-text strong a',
                '#' . preg_quote($row['title']) . '#'
            );
            $this->assertQueryContentRegex(
                '.panel-primary .panel-heading .right-text',
                '#' . preg_quote($row['company_name']) . '#'
            );
        }

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
}
