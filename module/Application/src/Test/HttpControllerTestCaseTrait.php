<?php
/**
 * ZF3 book Zend Framework Center Example Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/zendframework-center
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Application\Test;

use DOMElement;
use PHPUnit_Extensions_Database_DataSet_CsvDataSet;
use PHPUnit_Extensions_Database_DataSet_IDataSet;
use PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection;
use PHPUnit_Extensions_Database_DB_IDatabaseConnection;
use PHPUnit_Extensions_Database_TestCase_Trait;
use UserModel\Entity\UserEntity;
use Zend\Authentication\AuthenticationService;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Dom\Document;
use Zend\I18n\Translator\TranslatorInterface;

/**
 * Class HttpControllerTestCaseTrait
 *
 * @package Application\Test
 */
trait HttpControllerTestCaseTrait
{
    use PHPUnit_Extensions_Database_TestCase_Trait;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var Adapter
     */
    protected $adapter = null;

    /**
     * @var PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection
     */
    protected $connection = null;

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

        foreach ($this->csvTables as $table => $file) {
            $dataSet->addTable($table, $file);
        }

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

        /** @var AuthenticationService $authService */
        $authService = $this->getApplicationServiceLocator()->get(
            AuthenticationService::class
        );

        if ($authService->hasIdentity()) {
            $authService->clearIdentity();
        }

        $this->databaseTester = null;

        $this->getDatabaseTester()->setSetUpOperation(
            $this->getSetUpOperation()
        );
        $this->getDatabaseTester()->setDataSet($this->getDataSet());
        $this->getDatabaseTester()->onSetUp();
    }

    /**
     * Mock user login
     *
     * @param $role
     */
    protected function mockLogin($role)
    {
        $identity = new UserEntity();
        $identity->setEmail($role . '@zendframework.center');
        $identity->setRole($role);

        /** @var AuthenticationService $authService */
        $authService = $this->getApplicationServiceLocator()->get(
            AuthenticationService::class
        );
        $authService->getStorage()->write($identity);
    }

    /**
     * @param $formId
     *
     * @return null|string
     */
    protected function getCsrfValue($formId)
    {
        $dom  = new Document($this->getResponse()->getBody());
        $form = $dom->getDomDocument()->getElementById($formId);

        $csrfValue = null;

        /** @var DOMElement $node */
        foreach ($form->getElementsByTagName('input') as $key => $node) {
            if ('csrf' == $node->getAttribute('name')) {
                $csrfValue = $node->getAttribute('value');

                break;
            }
        }

        return $csrfValue;
    }

    /**
     * @param string $formId
     * @param array  $elementsToCheck
     *
     * @return array
     */
    protected function assertFormElementsExist(
        $formId, array $elementsToCheck = []
    ) {
        $dom  = new Document($this->getResponse()->getBody());
        $form = $dom->getDomDocument()->getElementById($formId);

        $elementsFound = [];

        /** @var DOMElement $node */
        foreach ($form->getElementsByTagName('input') as $key => $node) {
            $elementsFound[] = $node->getAttribute('name');
        }
        foreach ($form->getElementsByTagName('select') as $key => $node) {
            $elementsFound[] = $node->getAttribute('name');
        }
        foreach ($form->getElementsByTagName('textarea') as $key => $node)
        {
            $elementsFound[] = $node->getAttribute('name');
        }

        foreach ($elementsToCheck as $element) {
            $this->assertTrue(
                in_array($element, $elementsFound),
                'Form element "' . $element . '" not found in form "'
                . $formId . '"'
            );
        }
    }

    /**
     * @param string $formId
     * @param array  $elementValues
     *
     * @return array
     */
    protected function assertFormElementValues(
        $formId, array $elementValues = []
    ) {
        $dom  = new Document($this->getResponse()->getBody());
        $form = $dom->getDomDocument()->getElementById($formId);

        $valuesFound = [];

        /** @var DOMElement $node */
        foreach ($form->getElementsByTagName('input') as $key => $node) {
            $valuesFound[$node->getAttribute('name')]
                = $node->getAttribute('value');
        }
        foreach ($form->getElementsByTagName('select') as $key => $node) {
            $valuesFound[$node->getAttribute('name')]
                = $node->getAttribute('value');
        }
        foreach ($form->getElementsByTagName('textarea') as $key => $node)
        {
            $valuesFound[$node->getAttribute('name')]
                = $node->childNodes->item(0)->textContent;
        }

        foreach ($elementValues as $element => $value) {
            $this->assertEquals(
                $value,
                $valuesFound[$element],
                'Form element "' . $element . '" does not contain value "'
                . $value . '". Contains value "' . $valuesFound[$element]
                . '".'
            );
        }
    }

    /**
     * @param $select
     */
    protected function addCompanyJoinToQuery(Select $select)
    {
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
    }
}
