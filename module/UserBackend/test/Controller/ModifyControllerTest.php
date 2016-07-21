<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace UserBackendTest\Controller;

use Application\Test\HttpControllerTestCaseTrait;
use UserBackend\Controller\ModifyController;
use UserModel\Permissions\Role\AdminRole;
use Zend\Db\Sql\Sql;
use Zend\Stdlib\Parameters;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Class ModifyControllerTest
 *
 * @package UserBackendTest\Controller
 */
class ModifyControllerTest extends AbstractHttpControllerTestCase
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
     * Test add action can be accessed
     *
     * @group        controller
     * @group        user-backend
     */
    public function testAddActionCanBeAccessed()
    {
        $this->mockLogin(AdminRole::NAME);

        $url = '/de/user-backend/add';

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName('user-backend/modify');
        $this->assertModuleName('userbackend');
        $this->assertControllerName(ModifyController::class);
        $this->assertControllerClass('ModifyController');
        $this->assertActionName('add');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            $this->translator->translate(
                'user_backend_h1_display_add', 'default', 'de_DE'
            )
        );

        $this->assertFormElementsExist(
            'user_form',
            [
                'csrf', 'status', 'role', 'email', 'password',
                'save_user'
            ]
        );
    }

    /**
     * Test add action invalid data
     *
     * @group        controller
     * @group        user-backend
     */
    public function testAddActionInvalidData()
    {
        $this->mockLogin(AdminRole::NAME);

        $url = '/de/user-backend/add';

        $postArray = [
            'id'        => 4,
            'status'    => 'approved',
            'role'      => 'test',
            'email'     => 'email',
            'password'  => '1',
            'save_user' => 'save_user',
        ];

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $postArray['csrf'] = '123456';

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
                    'user_backend_message_form_timeout',
                    'default',
                    'de_DE'
                )
            ) . '#'
        );

        $this->assertQueryContentRegex(
            'form .form-group ul li',
            '#' . preg_quote(
                $this->translator->translate(
                    'user_model_message_role_invalid',
                    'default',
                    'de_DE'
                )
            ) . '#'
        );

        $this->assertQueryContentRegex(
            'form .form-group ul li',
            '#' . preg_quote(
                $this->translator->translate(
                    'user_model_message_email_invalid',
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
                    [8, 16],
                    $this->translator->translate(
                        'user_model_message_password_invalid',
                        'default',
                        'de_DE'
                    )
                )
            ) . '#'
        );

        $queryUser = $this->getConnection()->createQueryTable(
            'fetchUsersByPage',
            $this->generateQueryUserById($postArray['id'])
        );

        $this->assertEquals(0, $queryUser->getRowCount());

    }

    /**
     * Test add action successful handling
     *
     * @group        controller
     * @group        user-backend
     */
    public function testAddActionSuccessfulHandling()
    {
        $this->mockLogin(AdminRole::NAME);

        $url = '/de/user-backend/add';

        $postArray = [
            'id'        => 4,
            'status'    => 'approved',
            'role'      => 'company',
            'email'     => 'new-company@zendframework.center',
            'password'  => '12345789',
            'save_user' => 'save_user',
        ];

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $postArray['csrf'] = $this->getCsrfValue('user_form');

        $this->getRequest()
            ->setMethod('POST')
            ->setPost(new Parameters($postArray));

        $this->dispatch($url, 'POST');
        $this->assertResponseStatusCode(302);
        $this->assertRedirect();
        $this->assertRedirectTo('/de/user-backend/edit/4');

        $queryUser = $this->getConnection()->createQueryTable(
            'fetchUsersByPage',
            $this->generateQueryUserById($postArray['id'])
        );

        $row = $queryUser->getRow(0);

        $this->assertEquals($postArray['id'], $row['id']);
        $this->assertEquals($postArray['status'], $row['status']);
        $this->assertEquals($postArray['role'], $row['role']);
        $this->assertEquals($postArray['email'], $row['email']);
        $this->assertTrue(
            password_verify($postArray['password'], $row['password'])
        );
    }

    /**
     * Test edit action can be accessed
     *
     * @group        controller
     * @group        user-backend
     */
    public function testEditActionCanBeAccessed()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 1;
        $url = '/de/user-backend/edit/' . $id;

        $oldData = $this->getConnection()->createQueryTable(
            'fetchUsersByPage',
            $this->generateQueryUserById($id)
        )->getRow(0);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName('user-backend/modify');
        $this->assertModuleName('userbackend');
        $this->assertControllerName(ModifyController::class);
        $this->assertControllerClass('ModifyController');
        $this->assertActionName('edit');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            $this->translator->translate(
                'user_backend_h1_display_edit', 'default', 'de_DE'
            )
        );

        $this->assertFormElementsExist(
            'user_form',
            [
                'csrf', 'email', 'password', 'save_user'
            ]
        );

        $this->assertFormElementValues(
            'user_form',
            [
                'email'    => $oldData['email'],
                'password' => '',
            ]
        );
    }

    /**
     * Test edit action invalid data
     *
     * @group        controller
     * @group        user-backend
     */
    public function testEditActionInvalidData()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 1;
        $url = '/de/user-backend/edit/' . $id;

        $oldData = $this->getConnection()->createQueryTable(
            'fetchUsersByPage',
            $this->generateQueryUserById($id)
        )->getRow(0);

        $postArray = [
            'email'     => 'email',
            'password'  => '1',
            'save_user' => 'save_user',
        ];

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $postArray['csrf'] = '123456';

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
                    'user_backend_message_form_timeout',
                    'default',
                    'de_DE'
                )
            ) . '#'
        );

        $this->assertQueryContentRegex(
            'form .form-group ul li',
            '#' . preg_quote(
                $this->translator->translate(
                    'user_model_message_email_invalid',
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
                    [8, 16],
                    $this->translator->translate(
                        'user_model_message_password_invalid',
                        'default',
                        'de_DE'
                    )
                )
            ) . '#'
        );

        $queryUser = $this->getConnection()->createQueryTable(
            'fetchUsersByPage',
            $this->generateQueryUserById($id)
        );

        $row = $queryUser->getRow(0);

        $expectedRow = [
            'id'       => $id,
            'status'   => $oldData['status'],
            'role'     => $oldData['role'],
            'email'    => $oldData['email'],
            'password' => $oldData['password'],
        ];

        $this->assertEquals($expectedRow['id'], $row['id']);
        $this->assertEquals($expectedRow['status'], $row['status']);
        $this->assertEquals($expectedRow['role'], $row['role']);
        $this->assertEquals($expectedRow['email'], $row['email']);
        $this->assertEquals($expectedRow['password'], $row['password']);
    }

    /**
     * Test edit action successful handling
     *
     * @group        controller
     * @group        user-backend
     */
    public function testEditActionSuccessfulHandling()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 1;
        $url = '/de/user-backend/edit/' . $id;

        $oldData = $this->getConnection()->createQueryTable(
            'fetchUsersByPage',
            $this->generateQueryUserById($id)
        )->getRow(0);

        $postArray = [
            'email'     => 'neuer@firma.de',
            'password'   => '987654321',
            'save_user' => 'save_user',
        ];

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $postArray['csrf'] = $this->getCsrfValue('user_form');

        $this->getRequest()
            ->setMethod('POST')
            ->setPost(new Parameters($postArray));

        $this->dispatch($url, 'POST');
        $this->assertResponseStatusCode(302);
        $this->assertRedirect();
        $this->assertRedirectTo($url);

        $queryUser = $this->getConnection()->createQueryTable(
            'fetchUsersByPage',
            $this->generateQueryUserById($id)
        );

        $row = $queryUser->getRow(0);

        $expectedRow = [
            'id'      => $id,
            'status'  => $oldData['status'],
            'role'    => $oldData['role'],
            'email'   => $postArray['email'],
            'password' => $postArray['password'],
        ];

        $this->assertEquals($expectedRow['id'], $row['id']);
        $this->assertEquals($expectedRow['status'], $row['status']);
        $this->assertEquals($expectedRow['role'], $row['role']);
        $this->assertEquals($expectedRow['email'], $row['email']);
        $this->assertTrue(
            password_verify($postArray['password'], $row['password'])
        );
    }

    /**
     * Test delete action can be accessed
     *
     * @group        controller
     * @group        user-backend
     */
    public function testDeleteActionCanBeAccessed()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 1;
        $url = '/de/user-backend/delete/' . $id;

        $oldData = $this->getConnection()->createQueryTable(
            'fetchUsersByPage',
            $this->generateQueryUserById($id)
        )->getRow(0);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName('user-backend/modify');
        $this->assertModuleName('userbackend');
        $this->assertControllerName(ModifyController::class);
        $this->assertControllerClass('ModifyController');
        $this->assertActionName('delete');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            utf8_encode(
                $this->translator->translate(
                    'user_backend_h1_display_delete', 'default', 'de_DE'
                )
            )
        );

        $this->assertQueryContentRegex(
            'form .form-group .form-control-static',
            '#' . preg_quote($oldData['email']) . '#'
        );

        $queryUser = $this->getConnection()->createQueryTable(
            'fetchUsersByPage',
            $this->generateQueryUserById($id)
        );

        $this->assertEquals(1, $queryUser->getRowCount());
    }

    /**
     * Test delete action successful handling
     *
     * @group        controller
     * @group        user-backend
     */
    public function testDeleteActionSuccessfulHandling()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 1;
        $url = '/de/user-backend/delete/' . $id . '?delete=yes';

        $queryUser = $this->getConnection()->createQueryTable(
            'fetchUsersByPage',
            $this->generateQueryUserById($id)
        );

        $this->assertEquals(1, $queryUser->getRowCount());

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertRedirect();
        $this->assertRedirectTo('/de/user-backend');

        $queryUser = $this->getConnection()->createQueryTable(
            'fetchUsersByPage',
            $this->generateQueryUserById($id)
        );

        $this->assertEquals(0, $queryUser->getRowCount());
    }

    /**
     * Test approve action can be accessed
     *
     * @group        controller
     * @group        user-backend
     */
    public function testApproveActionCanBeAccessed()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 3;
        $url = '/de/user-backend/approve/' . $id;

        $oldData = $this->getConnection()->createQueryTable(
            'fetchUsersByPage',
            $this->generateQueryUserById($id)
        )->getRow(0);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName('user-backend/modify');
        $this->assertModuleName('userbackend');
        $this->assertControllerName(ModifyController::class);
        $this->assertControllerClass('ModifyController');
        $this->assertActionName('approve');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            utf8_encode(
                $this->translator->translate(
                    'user_backend_h1_display_approve', 'default',
                    'de_DE'
                )
            )
        );

        $this->assertQueryContentRegex(
            'form .form-group .form-control-static',
            '#' . preg_quote($oldData['email']) . '#'
        );

        $queryUser = $this->getConnection()->createQueryTable(
            'fetchUsersByPage',
            $this->generateQueryUserById($id)
        );

        $this->assertEquals(1, $queryUser->getRowCount());
    }

    /**
     * Test approve action successful handling
     *
     * @group        controller
     * @group        user-backend
     */
    public function testApproveActionSuccessfulHandling()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 3;
        $url = '/de/user-backend/approve/' . $id . '?approve=yes';

        $oldData = $this->getConnection()->createQueryTable(
            'fetchUsersByPage',
            $this->generateQueryUserById($id)
        )->getRow(0);

        $this->assertEquals('new', $oldData['status']);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertRedirect();
        $this->assertRedirectTo('/de/user-backend/show/' . $id);

        $queryUser = $this->getConnection()->createQueryTable(
            'fetchUsersByPage',
            $this->generateQueryUserById($id)
        );

        $row = $queryUser->getRow(0);

        $this->assertEquals('approved', $row['status']);
    }

    /**
     * Test block action can be accessed
     *
     * @group        controller
     * @group        user-backend
     */
    public function testBlockActionCanBeAccessed()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 3;
        $url = '/de/user-backend/block/' . $id;

        $oldData = $this->getConnection()->createQueryTable(
            'fetchUsersByPage',
            $this->generateQueryUserById($id)
        )->getRow(0);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName('user-backend/modify');
        $this->assertModuleName('userbackend');
        $this->assertControllerName(ModifyController::class);
        $this->assertControllerClass('ModifyController');
        $this->assertActionName('block');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            utf8_encode(
                $this->translator->translate(
                    'user_backend_h1_display_block', 'default', 'de_DE'
                )
            )
        );

        $this->assertQueryContentRegex(
            'form .form-group .form-control-static',
            '#' . preg_quote($oldData['email']) . '#'
        );

        $queryUser = $this->getConnection()->createQueryTable(
            'fetchUsersByPage',
            $this->generateQueryUserById($id)
        );

        $this->assertEquals(1, $queryUser->getRowCount());
    }

    /**
     * Test block action successful handling
     *
     * @group        controller
     * @group        user-backend
     */
    public function testBlockActionSuccessfulHandling()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 3;
        $url = '/de/user-backend/block/' . $id . '?block=yes';

        $oldData = $this->getConnection()->createQueryTable(
            'fetchUsersByPage',
            $this->generateQueryUserById($id)
        )->getRow(0);

        $this->assertEquals('new', $oldData['status']);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertRedirect();
        $this->assertRedirectTo('/de/user-backend/show/' . $id);

        $queryUser = $this->getConnection()->createQueryTable(
            'fetchUsersByPage',
            $this->generateQueryUserById($id)
        );

        $row = $queryUser->getRow(0);

        $this->assertEquals('blocked', $row['status']);
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
