<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace ApplicationTest\Controller;

use Application\Controller\IndexController;
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
 * Class IndexControllerTest
 *
 * @package ApplicationTest\Controller
 */
class IndexControllerTest extends AbstractHttpControllerTestCase
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
            PROJECT_ROOT . "/data/test-data/advert.homepage.test-data.csv"
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
     * Test index action without lang
     *
     * @group controller
     * @group application
     */
    public function testIndexActionWithoutLangCannotBeAccessed()
    {
        $this->dispatch('/', 'GET');
        $this->assertResponseStatusCode(301);
    }

    /**
     * Test index action with de lang
     *
     * @param $url
     * @param $locale
     *
     * @group        controller
     * @group        application
     * @dataProvider provideIndexActionCanBeAccessed
     */
    public function testIndexActionCanBeAccessed($url, $locale)
    {
        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName('home');
        $this->assertModuleName('application');
        $this->assertControllerName(IndexController::class);
        $this->assertControllerClass('IndexController');
        $this->assertActionName('index');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            $this->translator->translate(
                'application_h1_index_index', 'default', $locale
            )
        );
    }

    /**
     * @return array
     */
    public function provideIndexActionCanBeAccessed()
    {
        return [
            ['/de', 'de_DE'],
            ['/en', 'en_US'],
        ];
    }

    /**
     * Test index action output
     *
     * @group controller
     * @group application
     */
    public function testIndexActionRandomAdverts()
    {
        $this->dispatch('/de', 'GET');

        $queryAdvert = $this->getConnection()->createQueryTable(
            'fetchJob',
            $this->generateQueryAdvertRandomByType('job')
        );

        $row = $queryAdvert->getRow(0);

        $this->assertQueryContentRegex(
            '.panel-primary .panel-heading strong a',
            '#' . preg_quote($row['title']) . '#'
        );

        $queryProject = $this->getConnection()->createQueryTable(
            'fetchProject',
            $this->generateQueryAdvertRandomByType('project')
        );

        $row = $queryProject->getRow(0);

        $this->assertQueryContentRegex(
            '.panel-success .panel-heading strong a',
            '#' . preg_quote($row['title']) . '#'
        );
    }

    /**
     * @param string $type
     *
     * @return string
     */
    private function generateQueryAdvertRandomByType($type = 'job')
    {
        $sql = new Sql($this->adapter);

        $select = $sql->select('advert');
        $select->limit(1);
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
