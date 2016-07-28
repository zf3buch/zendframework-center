<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace CompanyBackendTest\Controller;

use Application\Test\HttpControllerTestCaseTrait;
use CompanyBackend\Controller\ModifyController;
use UserModel\Permissions\Role\AdminRole;
use Zend\Db\Sql\Sql;
use Zend\Stdlib\Parameters;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Class ModifyControllerTest
 *
 * @package CompanyBackendTest\Controller
 */
class ModifyControllerTest extends AbstractHttpControllerTestCase
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
     * Test add action can be accessed
     *
     * @group        controller
     * @group        company-backend
     */
    public function testAddActionCanBeAccessed()
    {
        $this->mockLogin(AdminRole::NAME);

        $url = '/de/company-backend/add';

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName('company-backend/modify');
        $this->assertModuleName('companybackend');
        $this->assertControllerName(ModifyController::class);
        $this->assertControllerClass('ModifyController');
        $this->assertActionName('add');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            $this->translator->translate(
                'company_backend_h1_display_add', 'default', 'de_DE'
            )
        );

        $this->assertFormElementsExist(
            'company_form',
            [
                'csrf', 'status', 'name', 'email', 'contact',
                'save_company'
            ]
        );
    }

    /**
     * Test add action invalid data
     *
     * @group        controller
     * @group        company-backend
     */
    public function testAddActionInvalidData()
    {
        $this->mockLogin(AdminRole::NAME);

        $url = '/de/company-backend/add';

        $postArray = [
            'id'           => 20,
            'csrf'         => '123456',
            'status'       => 'approved',
            'name'         => '',
            'email'        => 'email',
            'contact'      => '1',
            'save_company' => 'save_company',
        ];

        $this->getRequest()
            ->setMethod('POST')
            ->setPost(new Parameters($postArray));

        $this->dispatch($url, 'POST');
        $this->assertResponseStatusCode(200);
        $this->assertNotRedirect();

        $this->assertQueryContentRegex(
            '.alert-danger p',
            '#' . preg_quote(
                $this->translator->translate(
                    'company_backend_message_form_timeout',
                    'default',
                    'de_DE'
                )
            ) . '#'
        );

        $this->assertQueryContentRegex(
            'form .form-group ul li',
            '#' . preg_quote(
                $this->translator->translate(
                    'company_model_message_name_missing',
                    'default',
                    'de_DE'
                )
            ) . '#'
        );

        $this->assertQueryContentRegex(
            'form .form-group ul li',
            '#' . preg_quote(
                $this->translator->translate(
                    'company_model_message_email_invalid',
                    'default',
                    'de_DE'
                )
            ) . '#'
        );

        $this->assertQueryContentRegex(
            'form .form-group ul li',
            '#' . preg_quote(
                str_replace(
                    ['%min%', '%max%'],
                    [3, 64],
                    $this->translator->translate(
                        'company_model_message_contact_invalid',
                        'default',
                        'de_DE'
                    )
                )
            ) . '#'
        );

        $queryCompany = $this->getConnection()->createQueryTable(
            'fetchCompaniesByPage',
            $this->generateQueryCompanyById($postArray['id'])
        );

        $this->assertEquals(0, $queryCompany->getRowCount());

    }

    /**
     * Test add action successful handling
     *
     * @group        controller
     * @group        company-backend
     */
    public function testAddActionSuccessfulHandling()
    {
        $this->mockLogin(AdminRole::NAME);

        $url = '/de/company-backend/add';

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $postArray = [
            'id'           => 11,
            'csrf'         => $this->getCsrfValue('user_form'),
            'status'       => 'approved',
            'name'         => 'Neue Firma',
            'email'        => 'neuer@firma.de',
            'contact'      => 'Manuel Neuer',
            'save_company' => 'save_company',
        ];

        $this->getRequest()
            ->setMethod('POST')
            ->setPost(new Parameters($postArray));

        $this->dispatch($url, 'POST');
        $this->assertResponseStatusCode(302);
        $this->assertRedirect();
        $this->assertRedirectTo('/de/company-backend/edit/11');

        $queryCompany = $this->getConnection()->createQueryTable(
            'fetchCompaniesByPage',
            $this->generateQueryCompanyById($postArray['id'])
        );

        $row = $queryCompany->getRow(0);

        $this->assertEquals($postArray['id'], $row['id']);
        $this->assertEquals($postArray['status'], $row['status']);
        $this->assertEquals($postArray['name'], $row['name']);
        $this->assertEquals($postArray['email'], $row['email']);
        $this->assertEquals($postArray['contact'], $row['contact']);
    }

    /**
     * Test edit action can be accessed
     *
     * @group        controller
     * @group        company-backend
     */
    public function testEditActionCanBeAccessed()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 1;
        $url = '/de/company-backend/edit/' . $id;

        $oldData = $this->getConnection()->createQueryTable(
            'fetchCompaniesByPage',
            $this->generateQueryCompanyById($id)
        )->getRow(0);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName('company-backend/modify');
        $this->assertModuleName('companybackend');
        $this->assertControllerName(ModifyController::class);
        $this->assertControllerClass('ModifyController');
        $this->assertActionName('edit');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            $this->translator->translate(
                'company_backend_h1_display_edit', 'default', 'de_DE'
            )
        );

        $this->assertFormElementsExist(
            'company_form',
            [
                'csrf', 'name', 'email', 'contact', 'save_company'
            ]
        );

        $this->assertFormElementValues(
            'company_form',
            [
                'name'    => $oldData['name'],
                'email'   => $oldData['email'],
                'contact' => $oldData['contact'],
            ]
        );
    }

    /**
     * Test edit action invalid data
     *
     * @group        controller
     * @group        company-backend
     */
    public function testEditActionInvalidData()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 1;
        $url = '/de/company-backend/edit/' . $id;

        $oldData = $this->getConnection()->createQueryTable(
            'fetchCompaniesByPage',
            $this->generateQueryCompanyById($id)
        )->getRow(0);

        $postArray = [
            'csrf'         => '123456',
            'name'         => '',
            'email'        => 'email',
            'contact'      => '1',
            'save_company' => 'save_company',
        ];

        $this->getRequest()
            ->setMethod('POST')
            ->setPost(new Parameters($postArray));

        $this->dispatch($url, 'POST');
        $this->assertResponseStatusCode(200);
        $this->assertNotRedirect();

        $this->assertQueryContentRegex(
            '.alert-danger p',
            '#' . preg_quote(
                $this->translator->translate(
                    'company_backend_message_form_timeout',
                    'default',
                    'de_DE'
                )
            ) . '#'
        );

        $this->assertQueryContentRegex(
            'form .form-group ul li',
            '#' . preg_quote(
                $this->translator->translate(
                    'company_model_message_name_missing',
                    'default',
                    'de_DE'
                )
            ) . '#'
        );

        $this->assertQueryContentRegex(
            'form .form-group ul li',
            '#' . preg_quote(
                $this->translator->translate(
                    'company_model_message_email_invalid',
                    'default',
                    'de_DE'
                )
            ) . '#'
        );

        $this->assertQueryContentRegex(
            'form .form-group ul li',
            '#' . preg_quote(
                str_replace(
                    ['%min%', '%max%'],
                    [3, 64],
                    $this->translator->translate(
                        'company_model_message_contact_invalid',
                        'default',
                        'de_DE'
                    )
                )
            ) . '#'
        );

        $queryCompany = $this->getConnection()->createQueryTable(
            'fetchCompaniesByPage',
            $this->generateQueryCompanyById($id)
        );

        $row = $queryCompany->getRow(0);

        $expectedRow = [
            'id'      => $id,
            'status'  => $oldData['status'],
            'name'    => $oldData['name'],
            'email'   => $oldData['email'],
            'contact' => $oldData['contact'],
        ];

        $this->assertEquals($expectedRow['id'], $row['id']);
        $this->assertEquals($expectedRow['status'], $row['status']);
        $this->assertEquals($expectedRow['name'], $row['name']);
        $this->assertEquals($expectedRow['email'], $row['email']);
        $this->assertEquals($expectedRow['contact'], $row['contact']);
    }

    /**
     * Test edit action successful handling
     *
     * @group        controller
     * @group        company-backend
     */
    public function testEditActionSuccessfulHandling()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 1;
        $url = '/de/company-backend/edit/' . $id;

        $oldData = $this->getConnection()->createQueryTable(
            'fetchCompaniesByPage',
            $this->generateQueryCompanyById($id)
        )->getRow(0);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $postArray = [
            'csrf'         => $this->getCsrfValue('user_form'),
            'name'         => 'Neue Firma',
            'email'        => 'neuer@firma.de',
            'contact'      => 'Manuel Neuer',
            'save_company' => 'save_company',
        ];

        $this->getRequest()
            ->setMethod('POST')
            ->setPost(new Parameters($postArray));

        $this->dispatch($url, 'POST');
        $this->assertResponseStatusCode(302);
        $this->assertRedirect();
        $this->assertRedirectTo($url);

        $queryCompany = $this->getConnection()->createQueryTable(
            'fetchCompaniesByPage',
            $this->generateQueryCompanyById($id)
        );

        $row = $queryCompany->getRow(0);

        $expectedRow = [
            'id'      => $id,
            'status'  => $oldData['status'],
            'name'    => $postArray['name'],
            'email'   => $postArray['email'],
            'contact' => $postArray['contact'],
        ];

        $this->assertEquals($expectedRow['id'], $row['id']);
        $this->assertEquals($expectedRow['status'], $row['status']);
        $this->assertEquals($expectedRow['name'], $row['name']);
        $this->assertEquals($expectedRow['email'], $row['email']);
        $this->assertEquals($expectedRow['contact'], $row['contact']);
    }

    /**
     * Test delete action can be accessed
     *
     * @group        controller
     * @group        company-backend
     */
    public function testDeleteActionCanBeAccessed()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 1;
        $url = '/de/company-backend/delete/' . $id;

        $oldData = $this->getConnection()->createQueryTable(
            'fetchCompaniesByPage',
            $this->generateQueryCompanyById($id)
        )->getRow(0);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName('company-backend/modify');
        $this->assertModuleName('companybackend');
        $this->assertControllerName(ModifyController::class);
        $this->assertControllerClass('ModifyController');
        $this->assertActionName('delete');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            utf8_encode(
                $this->translator->translate(
                    'company_backend_h1_display_delete', 'default', 'de_DE'
                )
            )
        );

        $this->assertQueryContentRegex(
            'form .form-group .form-control-static',
            '#' . preg_quote($oldData['name']) . '#'
        );

        $queryCompany = $this->getConnection()->createQueryTable(
            'fetchCompaniesByPage',
            $this->generateQueryCompanyById($id)
        );

        $this->assertEquals(1, $queryCompany->getRowCount());
    }

    /**
     * Test delete action successful handling
     *
     * @group        controller
     * @group        company-backend
     */
    public function testDeleteActionSuccessfulHandling()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 1;
        $url = '/de/company-backend/delete/' . $id . '?delete=yes';

        $queryCompany = $this->getConnection()->createQueryTable(
            'fetchCompaniesByPage',
            $this->generateQueryCompanyById($id)
        );

        $this->assertEquals(1, $queryCompany->getRowCount());

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertRedirect();
        $this->assertRedirectTo('/de/company-backend');

        $queryCompany = $this->getConnection()->createQueryTable(
            'fetchCompaniesByPage',
            $this->generateQueryCompanyById($id)
        );

        $this->assertEquals(0, $queryCompany->getRowCount());
    }

    /**
     * Test approve action can be accessed
     *
     * @group        controller
     * @group        company-backend
     */
    public function testApproveActionCanBeAccessed()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 10;
        $url = '/de/company-backend/approve/' . $id;

        $oldData = $this->getConnection()->createQueryTable(
            'fetchCompaniesByPage',
            $this->generateQueryCompanyById($id)
        )->getRow(0);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName('company-backend/modify');
        $this->assertModuleName('companybackend');
        $this->assertControllerName(ModifyController::class);
        $this->assertControllerClass('ModifyController');
        $this->assertActionName('approve');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            utf8_encode(
                $this->translator->translate(
                    'company_backend_h1_display_approve', 'default',
                    'de_DE'
                )
            )
        );

        $this->assertQueryContentRegex(
            'form .form-group .form-control-static',
            '#' . preg_quote($oldData['name']) . '#'
        );

        $queryCompany = $this->getConnection()->createQueryTable(
            'fetchCompaniesByPage',
            $this->generateQueryCompanyById($id)
        );

        $this->assertEquals(1, $queryCompany->getRowCount());
    }

    /**
     * Test approve action successful handling
     *
     * @group        controller
     * @group        company-backend
     */
    public function testApproveActionSuccessfulHandling()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 10;
        $url = '/de/company-backend/approve/' . $id . '?approve=yes';

        $oldData = $this->getConnection()->createQueryTable(
            'fetchCompaniesByPage',
            $this->generateQueryCompanyById($id)
        )->getRow(0);

        $this->assertEquals('new', $oldData['status']);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertRedirect();
        $this->assertRedirectTo('/de/company-backend/show/' . $id);

        $queryCompany = $this->getConnection()->createQueryTable(
            'fetchCompaniesByPage',
            $this->generateQueryCompanyById($id)
        );

        $row = $queryCompany->getRow(0);

        $this->assertEquals('approved', $row['status']);
    }

    /**
     * Test block action can be accessed
     *
     * @group        controller
     * @group        company-backend
     */
    public function testBlockActionCanBeAccessed()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 10;
        $url = '/de/company-backend/block/' . $id;

        $oldData = $this->getConnection()->createQueryTable(
            'fetchCompaniesByPage',
            $this->generateQueryCompanyById($id)
        )->getRow(0);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName('company-backend/modify');
        $this->assertModuleName('companybackend');
        $this->assertControllerName(ModifyController::class);
        $this->assertControllerClass('ModifyController');
        $this->assertActionName('block');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            utf8_encode(
                $this->translator->translate(
                    'company_backend_h1_display_block', 'default', 'de_DE'
                )
            )
        );

        $this->assertQueryContentRegex(
            'form .form-group .form-control-static',
            '#' . preg_quote($oldData['name']) . '#'
        );

        $queryCompany = $this->getConnection()->createQueryTable(
            'fetchCompaniesByPage',
            $this->generateQueryCompanyById($id)
        );

        $this->assertEquals(1, $queryCompany->getRowCount());
    }

    /**
     * Test block action successful handling
     *
     * @group        controller
     * @group        company-backend
     */
    public function testBlockActionSuccessfulHandling()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 10;
        $url = '/de/company-backend/block/' . $id . '?block=yes';

        $oldData = $this->getConnection()->createQueryTable(
            'fetchCompaniesByPage',
            $this->generateQueryCompanyById($id)
        )->getRow(0);

        $this->assertEquals('new', $oldData['status']);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertRedirect();
        $this->assertRedirectTo('/de/company-backend/show/' . $id);

        $queryCompany = $this->getConnection()->createQueryTable(
            'fetchCompaniesByPage',
            $this->generateQueryCompanyById($id)
        );

        $row = $queryCompany->getRow(0);

        $this->assertEquals('blocked', $row['status']);
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
