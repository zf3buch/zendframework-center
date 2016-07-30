<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace AdvertBackendTest\Controller;

use AdvertBackend\Controller\ModifyController;
use Application\Test\HttpControllerTestCaseTrait;
use UserModel\Permissions\Role\AdminRole;
use Zend\Db\Sql\Sql;
use Zend\Stdlib\Parameters;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Class ModifyControllerTest
 *
 * @package AdvertBackendTest\Controller
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
            'advert'  => PROJECT_ROOT
                . "/data/test-data/advert.test-data.csv",
        ];

    /**
     * Test add action can be accessed
     *
     * @group        controller
     * @group        advert-backend
     */
    public function testAddActionCanBeAccessed()
    {
        $this->mockLogin(AdminRole::NAME);

        $url = '/de/advert-backend/add';

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName('advert-backend/modify');
        $this->assertModuleName('advertbackend');
        $this->assertControllerName(ModifyController::class);
        $this->assertControllerClass('ModifyController');
        $this->assertActionName('add');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            $this->translator->translate(
                'advert_backend_h1_display_add', 'default', 'de_DE'
            )
        );

        $this->assertFormElementsExist(
            'advert_form',
            [
                'csrf', 'type', 'status', 'company', 'location', 'title',
                'text', 'save_advert'
            ]
        );
    }

    /**
     * Test add action invalid data
     *
     * @group        controller
     * @group        advert-backend
     */
    public function testAddActionInvalidData()
    {
        $this->mockLogin(AdminRole::NAME);

        $url = '/de/advert-backend/add';

        $postArray = [
            'id'          => 20,
            'csrf'        => '123456',
            'type'        => 'job',
            'status'      => 'approved',
            'company'     => '99',
            'location'    => '',
            'title'       => 'Test advert',
            'text'        => '<p>Description for test advert</p>',
            'save_advert' => 'save_advert',
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
                    'advert_backend_message_form_timeout',
                    'default',
                    'de_DE'
                )
            ) . '#'
        );

        $this->assertQueryContentRegex(
            'form .form-group ul li',
            '#' . preg_quote(
                $this->translator->translate(
                    'advert_model_message_company_invalid',
                    'default',
                    'de_DE'
                )
            ) . '#'
        );

        $this->assertQueryContentRegex(
            'form .form-group ul li',
            '#' . preg_quote(
                $this->translator->translate(
                    'advert_model_message_location_missing',
                    'default',
                    'de_DE'
                )
            ) . '#'
        );

        $this->assertQueryContentRegex(
            'form .form-group ul li',
            '#' . preg_quote(
                str_replace(
                    '%min%',
                    200,
                    $this->translator->translate(
                        'advert_model_message_text_invalid',
                        'default',
                        'de_DE'
                    )
                )
            ) . '#'
        );

        $queryAdvert = $this->getConnection()->createQueryTable(
            'fetchAdvertsByPage',
            $this->generateQueryAdvertById($postArray['id'])
        );

        $this->assertEquals(0, $queryAdvert->getRowCount());

    }

    /**
     * Test add action successful handling
     *
     * @group        controller
     * @group        advert-backend
     */
    public function testAddActionSuccessfulHandling()
    {
        $this->mockLogin(AdminRole::NAME);

        $url = '/de/advert-backend/add';

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $postArray = [
            'id'          => 20,
            'csrf'        => $this->getCsrfValue('advert_form'),
            'type'        => 'job',
            'status'      => 'approved',
            'company'     => '3',
            'location'    => 'Hamburg',
            'title'       => 'Test advert',
            'text'        => str_repeat(
                '<p>Description for test advert</p>', 10
            ),
            'save_advert' => 'save_advert',
        ];

        $this->getRequest()
            ->setMethod('POST')
            ->setPost(new Parameters($postArray));

        $this->dispatch($url, 'POST');
        $this->assertResponseStatusCode(302);
        $this->assertRedirect();
        $this->assertRedirectTo('/de/advert-backend/edit/20');

        $queryAdvert = $this->getConnection()->createQueryTable(
            'fetchAdvertsByPage',
            $this->generateQueryAdvertById($postArray['id'])
        );

        $row = $queryAdvert->getRow(0);

        $this->assertEquals($postArray['id'], $row['id']);
        $this->assertEquals($postArray['type'], $row['type']);
        $this->assertEquals($postArray['status'], $row['status']);
        $this->assertEquals($postArray['company'], $row['company']);
        $this->assertEquals($postArray['location'], $row['location']);
        $this->assertEquals($postArray['title'], $row['title']);
        $this->assertEquals($postArray['text'], $row['text']);
    }

    /**
     * Test edit action can be accessed
     *
     * @group        controller
     * @group        advert-backend
     */
    public function testEditActionCanBeAccessed()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 1;
        $url = '/de/advert-backend/edit/' . $id;

        $oldData = $this->getConnection()->createQueryTable(
            'fetchAdvertsByPage',
            $this->generateQueryAdvertById($id)
        )->getRow(0);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName('advert-backend/modify');
        $this->assertModuleName('advertbackend');
        $this->assertControllerName(ModifyController::class);
        $this->assertControllerClass('ModifyController');
        $this->assertActionName('edit');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            $this->translator->translate(
                'advert_backend_h1_display_edit', 'default', 'de_DE'
            )
        );

        $this->assertFormElementsExist(
            'advert_form',
            [
                'csrf', 'location', 'title', 'text', 'save_advert'
            ]
        );

        $this->assertFormElementValues(
            'advert_form',
            [
                'location' => $oldData['location'],
                'title'    => $oldData['title'],
                'text'     => $oldData['text'],
            ]
        );
    }

    /**
     * Test edit action invalid data
     *
     * @group        controller
     * @group        advert-backend
     */
    public function testEditActionInvalidData()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 1;
        $url = '/de/advert-backend/edit/' . $id;

        $oldData = $this->getConnection()->createQueryTable(
            'fetchAdvertsByPage',
            $this->generateQueryAdvertById($id)
        )->getRow(0);

        $postArray = [
            'csrf'        => '123456',
            'location'    => '',
            'title'       => 'Test advert',
            'text'        => '<p>Description for test advert</p>',
            'save_advert' => 'save_advert',
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
                    'advert_backend_message_form_timeout',
                    'default',
                    'de_DE'
                )
            ) . '#'
        );

        $this->assertQueryContentRegex(
            'form .form-group ul li',
            '#' . preg_quote(
                $this->translator->translate(
                    'advert_model_message_location_missing',
                    'default',
                    'de_DE'
                )
            ) . '#'
        );

        $this->assertQueryContentRegex(
            'form .form-group ul li',
            '#' . preg_quote(
                str_replace(
                    '%min%',
                    200,
                    $this->translator->translate(
                        'advert_model_message_text_invalid',
                        'default',
                        'de_DE'
                    )
                )
            ) . '#'
        );

        $queryAdvert = $this->getConnection()->createQueryTable(
            'fetchAdvertsByPage',
            $this->generateQueryAdvertById($id)
        );

        $row = $queryAdvert->getRow(0);

        $expectedRow = [
            'id'       => $id,
            'status'   => $oldData['status'],
            'type'     => $oldData['type'],
            'company'  => $oldData['company'],
            'title'    => $oldData['title'],
            'text'     => $oldData['text'],
            'location' => $oldData['location'],
        ];

        $this->assertEquals($expectedRow['id'], $row['id']);
        $this->assertEquals($expectedRow['type'], $row['type']);
        $this->assertEquals($expectedRow['status'], $row['status']);
        $this->assertEquals($expectedRow['company'], $row['company']);
        $this->assertEquals($expectedRow['location'], $row['location']);
        $this->assertEquals($expectedRow['title'], $row['title']);
    }

    /**
     * Test edit action successful handling
     *
     * @group        controller
     * @group        advert-backend
     */
    public function testEditActionSuccessfulHandling()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 1;
        $url = '/de/advert-backend/edit/' . $id;

        $oldData = $this->getConnection()->createQueryTable(
            'fetchAdvertsByPage',
            $this->generateQueryAdvertById($id)
        )->getRow(0);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $postArray = [
            'csrf'        => $this->getCsrfValue('advert_form'),
            'location'    => 'Hamburg',
            'title'       => 'Test advert',
            'text'        => str_repeat(
                '<p>Description for test advert</p>', 10
            ),
            'save_advert' => 'save_advert',
        ];

        $this->getRequest()
            ->setMethod('POST')
            ->setPost(new Parameters($postArray));

        $this->dispatch($url, 'POST');
        $this->assertResponseStatusCode(302);
        $this->assertRedirect();
        $this->assertRedirectTo($url);

        $queryAdvert = $this->getConnection()->createQueryTable(
            'fetchAdvertsByPage',
            $this->generateQueryAdvertById($id)
        );

        $row = $queryAdvert->getRow(0);

        $expectedRow = [
            'id'       => $id,
            'status'   => $oldData['status'],
            'type'     => $oldData['type'],
            'company'  => $oldData['company'],
            'title'    => $postArray['title'],
            'text'     => $postArray['text'],
            'location' => $postArray['location'],
        ];

        $this->assertEquals($expectedRow['id'], $row['id']);
        $this->assertEquals($expectedRow['type'], $row['type']);
        $this->assertEquals($expectedRow['status'], $row['status']);
        $this->assertEquals($expectedRow['company'], $row['company']);
        $this->assertEquals($expectedRow['location'], $row['location']);
        $this->assertEquals($expectedRow['title'], $row['title']);
        $this->assertEquals($expectedRow['text'], $row['text']);
    }

    /**
     * Test delete action can be accessed
     *
     * @group        controller
     * @group        advert-backend
     */
    public function testDeleteActionCanBeAccessed()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 1;
        $url = '/de/advert-backend/delete/' . $id;

        $oldData = $this->getConnection()->createQueryTable(
            'fetchAdvertsByPage',
            $this->generateQueryAdvertById($id)
        )->getRow(0);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName('advert-backend/modify');
        $this->assertModuleName('advertbackend');
        $this->assertControllerName(ModifyController::class);
        $this->assertControllerClass('ModifyController');
        $this->assertActionName('delete');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            utf8_encode(
                $this->translator->translate(
                    'advert_backend_h1_display_delete', 'default', 'de_DE'
                )
            )
        );

        $this->assertQueryContentRegex(
            'form .form-group .form-control-static',
            '#' . preg_quote($oldData['title']) . '#'
        );

        $queryAdvert = $this->getConnection()->createQueryTable(
            'fetchAdvertsByPage',
            $this->generateQueryAdvertById($id)
        );

        $this->assertEquals(1, $queryAdvert->getRowCount());
    }

    /**
     * Test delete action successful handling
     *
     * @group        controller
     * @group        advert-backend
     */
    public function testDeleteActionSuccessfulHandling()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 1;
        $url = '/de/advert-backend/delete/' . $id . '?delete=yes';

        $queryAdvert = $this->getConnection()->createQueryTable(
            'fetchAdvertsByPage',
            $this->generateQueryAdvertById($id)
        );

        $this->assertEquals(1, $queryAdvert->getRowCount());

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertRedirect();
        $this->assertRedirectTo('/de/advert-backend');

        $queryAdvert = $this->getConnection()->createQueryTable(
            'fetchAdvertsByPage',
            $this->generateQueryAdvertById($id)
        );

        $this->assertEquals(0, $queryAdvert->getRowCount());
    }

    /**
     * Test approve action can be accessed
     *
     * @group        controller
     * @group        advert-backend
     */
    public function testApproveActionCanBeAccessed()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 19;
        $url = '/de/advert-backend/approve/' . $id;

        $oldData = $this->getConnection()->createQueryTable(
            'fetchAdvertsByPage',
            $this->generateQueryAdvertById($id)
        )->getRow(0);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName('advert-backend/modify');
        $this->assertModuleName('advertbackend');
        $this->assertControllerName(ModifyController::class);
        $this->assertControllerClass('ModifyController');
        $this->assertActionName('approve');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            utf8_encode(
                $this->translator->translate(
                    'advert_backend_h1_display_approve', 'default', 'de_DE'
                )
            )
        );

        $this->assertQueryContentRegex(
            'form .form-group .form-control-static',
            '#' . preg_quote($oldData['title']) . '#'
        );

        $queryAdvert = $this->getConnection()->createQueryTable(
            'fetchAdvertsByPage',
            $this->generateQueryAdvertById($id)
        );

        $this->assertEquals(1, $queryAdvert->getRowCount());
    }

    /**
     * Test approve action successful handling
     *
     * @group        controller
     * @group        advert-backend
     */
    public function testApproveActionSuccessfulHandling()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 19;
        $url = '/de/advert-backend/approve/' . $id . '?approve=yes';

        $oldData = $this->getConnection()->createQueryTable(
            'fetchAdvertsByPage',
            $this->generateQueryAdvertById($id)
        )->getRow(0);

        $this->assertEquals('new', $oldData['status']);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertRedirect();
        $this->assertRedirectTo('/de/advert-backend/show/' . $id);

        $queryAdvert = $this->getConnection()->createQueryTable(
            'fetchAdvertsByPage',
            $this->generateQueryAdvertById($id)
        );

        $row = $queryAdvert->getRow(0);

        $this->assertEquals('approved', $row['status']);
    }

    /**
     * Test block action can be accessed
     *
     * @group        controller
     * @group        advert-backend
     */
    public function testBlockActionCanBeAccessed()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 19;
        $url = '/de/advert-backend/block/' . $id;

        $oldData = $this->getConnection()->createQueryTable(
            'fetchAdvertsByPage',
            $this->generateQueryAdvertById($id)
        )->getRow(0);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(200);

        $this->assertMatchedRouteName('advert-backend/modify');
        $this->assertModuleName('advertbackend');
        $this->assertControllerName(ModifyController::class);
        $this->assertControllerClass('ModifyController');
        $this->assertActionName('block');

        $this->assertQuery('.page-header h1');
        $this->assertQueryContentContains(
            '.page-header h1',
            utf8_encode(
                $this->translator->translate(
                    'advert_backend_h1_display_block', 'default', 'de_DE'
                )
            )
        );

        $this->assertQueryContentRegex(
            'form .form-group .form-control-static',
            '#' . preg_quote($oldData['title']) . '#'
        );

        $queryAdvert = $this->getConnection()->createQueryTable(
            'fetchAdvertsByPage',
            $this->generateQueryAdvertById($id)
        );

        $this->assertEquals(1, $queryAdvert->getRowCount());
    }

    /**
     * Test block action successful handling
     *
     * @group        controller
     * @group        advert-backend
     */
    public function testBlockActionSuccessfulHandling()
    {
        $this->mockLogin(AdminRole::NAME);

        $id  = 19;
        $url = '/de/advert-backend/block/' . $id . '?block=yes';

        $oldData = $this->getConnection()->createQueryTable(
            'fetchAdvertsByPage',
            $this->generateQueryAdvertById($id)
        )->getRow(0);

        $this->assertEquals('new', $oldData['status']);

        $this->dispatch($url, 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertRedirect();
        $this->assertRedirectTo('/de/advert-backend/show/' . $id);

        $queryAdvert = $this->getConnection()->createQueryTable(
            'fetchAdvertsByPage',
            $this->generateQueryAdvertById($id)
        );

        $row = $queryAdvert->getRow(0);

        $this->assertEquals('blocked', $row['status']);
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
