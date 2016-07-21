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
use Application\Test\HttpControllerTestCaseTrait;
use Zend\Db\Sql\Sql;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Class IndexControllerTest
 *
 * @package ApplicationTest\Controller
 */
class IndexControllerTest extends AbstractHttpControllerTestCase
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
                . "/data/test-data/advert.homepage.test-data.csv",
        ];

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
    protected function generateQueryAdvertRandomByType($type = 'job')
    {
        $sql = new Sql($this->adapter);

        $select = $sql->select('advert');
        $select->limit(1);
        $select->where->equalTo('advert.type', $type);

        $this->addCompanyJoinToQuery($select);

        return $sql->buildSqlString($select);
    }
}
