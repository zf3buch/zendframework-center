<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace CompanyBackendTest\Controller;

use CompanyBackend\Controller\DisplayController;
use Application\Test\HttpControllerTestCaseTrait;
use UserModel\Permissions\Role\AdminRole;
use Zend\Db\Sql\Sql;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Class DisplayControllerTest
 *
 * @package CompanyBackendTest\Controller
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
     * @group        company-backend
     * @dataProvider provideIndexActionCanBeAccessed
     */
    public function testIndexActionCanBeAccessed($url, $locale, $route, $h1
    ) {
        $this->mockLogin(AdminRole::NAME);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName($route);
        $this->assertModuleName('companybackend');
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
                '/de/company-backend', 'de_DE', 'company-backend',
                'company_backend_h1_display_index'
            ],
            [
                '/en/company-backend', 'en_US', 'company-backend',
                'company_backend_h1_display_index'
            ],
        ];
    }

    /**
     * Test index action output
     *
     * @param $page
     *
     * @group        controller
     * @group        company-backend
     */
    public function testIndexActionCompanyOutput()
    {
        $this->mockLogin(AdminRole::NAME);

        $page = 1;
        $url = '/de/company-backend';

        $this->dispatch($url, 'GET');

        $queryCompany = $this->getConnection()->createQueryTable(
            'fetchCompaniesByPage',
            $this->generateQueryCompaniesByPage($page)
        );

        for ($count = 0; $count < $queryCompany->getRowCount(); $count++) {
            $row = $queryCompany->getRow($count);

            $this->assertQueryContentRegex(
                'table tbody tr td a',
                '#' . preg_quote($row['name']) . '#'
            );
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
     * @group        company-backend
     * @dataProvider provideDetailActionCanBeAccessed
     */
    public function testDetailActionCanBeAccessed($id)
    {
        $this->mockLogin(AdminRole::NAME);

        $queryCompany = $this->getConnection()->createQueryTable(
            'fetchCompaniesByPage',
            $this->generateQueryCompanyById($id)
        );

        $row = $queryCompany->getRow(0);

        $url = '/de/company-backend/show/' . $id;

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName(
            'company-backend/show'
        );
        $this->assertModuleName('companybackend');
        $this->assertControllerName(DisplayController::class);
        $this->assertControllerClass('DisplayController');
        $this->assertActionName('show');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            $this->translator->translate('company_backend_h1_display_show')
        );
    }

    /**
     * @return array
     */
    public function provideDetailActionCanBeAccessed()
    {
        return [
            [1], [3], [5], [7], [8], [9],
        ];
    }

    /**
     * @param int $page
     *
     * @return string
     */
    protected function generateQueryCompaniesByPage($page = 1)
    {
        $limit  = 15;
        $offset = ($page - 1) * $limit;

        $sql = new Sql($this->adapter);

        $select = $sql->select('company');
        $select->limit($limit);
        $select->offset($offset);
        $select->order(['company.registered' => 'DESC']);

        return $sql->buildSqlString($select);
    }

    /**
     * @param int $id
     *
     * @return string
     */
    protected function generateQueryCompanyById($id)
    {
        $sql = new Sql($this->adapter);

        $select = $sql->select('company');
        $select->where->equalTo('company.id', $id);

        return $sql->buildSqlString($select);
    }
}
