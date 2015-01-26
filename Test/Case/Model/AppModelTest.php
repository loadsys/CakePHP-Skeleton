<?php
App::uses('AppModel', 'Model');
App::uses('AttachmentBehavior', 'Uploader.Model/Behavior');

/**
 * AppModel Test Case
 *
 */
class TestAppModel extends AppModel {
	public function __construct() {
		// Do nothing.
	}
	public function constructVirtualFields() {
		return parent::constructVirtualFields();
	}
	public function constructValidate() {
		return parent::constructValidate();
	}
	public function constructOrderProperty() {
		return parent::constructOrderProperty();
	}
}

/**
 * AppModel Test Case
 *
 */
class AppModelTest extends CakeTestCase {

	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = array(
		'app.app_model',
	);

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$this->AppModel = ClassRegistry::init('AppModel');
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->AppModel);
		parent::tearDown();
	}

	/**
	 * testConstructVirtualFields method
	 *
	 * @covers AppModel::__construct
	 * @covers AppModel::constructVirtualFields
	 * @return void
	 */
	public function testConstructVirtualFields() {
		$expected = array(
			'name' => 'CONCAT(`Person`.`firstname`, " ", `Person`.`lastname`)',
		);

		$Model = new TestAppModel();
		$Model->name = 'AppModel';
		$Model->alias = 'Person';
		$Model->virtualFields = array(
			'name' => 'CONCAT(`AppModel`.`firstname`, " ", `AppModel`.`lastname`)',
		);

		$Model->constructVirtualFields();
		$this->assertEquals($expected, $Model->virtualFields);
	}

	/**
	 * testConstructValidate method
	 *
	 * @covers AppModel::__construct
	 * @covers AppModel::constructValidate
	 * @return void
	 */
	public function testConstructValidate() {
		$expected = array(
			'name' => 'notEmpty',
		);

		$Model = new TestAppModel();
		$Model->validate = $expected;

		$Model->constructValidate();
		$this->assertEquals($expected, $Model->validate, 'The validate property should be "unchanged" since no dynamic rules were specified.');
	}

	/**
	 * Confirm that when ::$order is a string, quoted table name is replaced
	 * and unquoted is not.
	 *
	 * @covers AppModel::__construct
	 * @covers AppModel::constructOrderProperty
	 * @return void
	 */
	public function testConstructOrderPropertyAsString() {

		$Model = new TestAppModel();
		$Model->name = 'Original';
		$Model->alias = 'Aliased';

		$Model->order = 'Original.active ASC';
		$expected = 'Original.active ASC';
		$Model->constructOrderProperty();
		$this->assertEquals($expected, $Model->order);

		$Model->order = '`Original`.`active` ASC';
		$expected = '`Aliased`.`active` ASC';
		$Model->constructOrderProperty();
		$this->assertEquals($expected, $Model->order);
	}

	/**
	 * Confirm that when ::$order is an array, quoted table names are replaced
	 * and unquoted are not.
	 *
	 * @covers AppModel::constructOrderProperty
	 * @return void
	 */
	public function testConstructOrderPropertyAsArray() {
		$Model = new TestAppModel();
		$Model->name = 'Original';
		$Model->alias = 'Aliased';

		$Model->order = array(
			'name' => 'ASC',
			'`Original`.`dob`' => 'ASC',
			'Original.active' => 'ASC',
			'`Original`.`dob` ASC',
			'Original.active ASC',
		);

		$expected = array(
			'name' => 'ASC',
			'`Aliased`.`dob`' => 'ASC',
			'Original.active' => 'ASC',
			'`Aliased`.`dob` ASC',
			'Original.active ASC',
		);
		$Model->constructOrderProperty();
		$this->assertEquals($expected, $Model->order);
	}

	/**
	 * Crudely test the template getList() method.
	 *
	 * @covers AppModel::getList
	 * @return void
	 */
	public function testGetList() {
		$this->AppModel = $this->getMockForModel('AppModel', array('enumList'));
		$this->AppModel->expects($this->once())
			->method('enumList')
			->with(array(), null)
			->will($this->returnValue(array()));
		$this->assertEquals(array(), $this->AppModel->getList());
	}

	/**
	 * Provide [properties, key, expected, msg] sets to testEnumList().
	 *
	 * @return array	Sets of [property array, key string, expected value, phpunit messge].
	 */
	public function provideEnumListArgs() {
		$properties = array(
			'field1' => array(
				'f1-opt1' => 'Field 1, Option 1',
				'f1-opt2' => 'Field 1, Option 2',
			),
			'field2' => array(
				'f2-opt1' => 'Field 2, Option 1',
				'f2-opt2' => 'Field 2, Option 2',
			),
		);
		return array(
			'pass-null' => array(
				$properties,
				null,
				$properties,
				'Passing null should result in the entire properties array returned.',
			),
			'pass-key' => array(
				$properties,
				'field2',
				$properties['field2'],
				'Passing a valid field should return that sub-array.',
			),
			'pass-invalid' => array(
				$properties,
				'not-there',
				false,
				'Passing an invalid field should return false.',
			),
		);
	}

	/**
	 * Test the 3 branches of logic in enumList().
	 *
	 * @covers AppModel::enumList
	 * @dataProvider provideEnumListArgs
	 * @param	array	$properties	A properties array as defined in getList().
	 * @param	string|null	$key	Key to look up in $properties, or null.
	 * @param	mixed	$expected	The expected result from enumList().
	 * @param	string	$msg	PHPUnit error message to display if the assertion fails.
	 * @return void
	 */
	public function testEnumList($properties, $key, $expected, $msg = '') {
		$this->AppModel = ClassRegistry::init('AppModel');
		$this->assertEquals(
			$expected,
			$this->AppModel->enumList($properties, $key),
			$msg
		);
	}

	/**
	 * testFullTableName method
	 *
	 * @covers AppModel::fullTableName
	 * @return void
	 */
	public function testFullTableName() {
		$quote = true;
		$schema = false;

		// Create a datasource object that responds to fullTableName().
		$datasource = $this->getMock('DataSource', array('fullTableName'));
		$datasource->expects($this->once())
			->method('fullTableName')
			->with($this->anything(), $quote, $schema)
			->will($this->returnValue('canary'));

		// Set up the injector to return the mocked datasource object.
		$model = $this->getMockForModel('AppModel', array('GetDataSource'));
		$model->expects($this->once())
			->method('GetDataSource')
			->with()
			->will($this->returnValue($datasource));

		$this->assertEquals('canary', $model->fullTableName($quote, $schema));
	}

	/**
	 * testTruncate method
	 *
	 * @covers AppModel::truncate
	 * @return void
	 */
	public function testTruncate() {
		$model = $this->getMockForModel('AppModel', array('fullTableName', 'query'));
		$model->expects($this->once())
			->method('fullTableName')
			->with()
			->will($this->returnValue('`canary`'));
		$model->expects($this->once())
			->method('query')
			->with('TRUNCATE TABLE `canary`;')
			->will($this->returnValue(true));

		$this->assertTrue($model->truncate());
	}

	/**
	 * Confirm that getTranslatedFields() returns an empty array for models
	 * that do not employ the Translate behavior, or do not have individual
	 * translated fields defined.
	 *
	 * @covers AppModel::getTranslatedFields
	 * @return void
	 */
	public function testGetTranslatedFieldsWhenModelNotTranslated() {
		$Model = new TestAppModel();

		// Not translated.
		$Model->actsAs = array();
		$result = $Model->getTranslatedFields();
		$this->assertInternalType('array', $result);
		$this->assertEmpty($result);

		// Translated, but no custom fields defined.
		$Model->actsAs = array('Translate');
		$result = $Model->getTranslatedFields();
		$this->assertInternalType('array', $result);
		$this->assertEmpty($result);
	}

	/**
	 * Confirm that getTranslatedFields() extracts correct values from a
	 * model's populated $actsAs['Translate'] array.
	 *
	 * @covers AppModel::getTranslatedFields
	 * @return void
	 */
	public function testGetTranslatedFields() {
		$expected = array(
			'simpleField',
			'fancyField',
		);

		$Model = new TestAppModel();
		$Model->actsAs = array(
			'Translate' => array(
				'simpleField',
				'fancyField' => 'fancyField_i18n',
			),
		);

		$result = $Model->getTranslatedFields();
		$this->assertEquals($expected, $result);
	}

	/**
	 * testValidateUnique method
	 *
	 * @covers AppModel::validateUnique
	 * @return void
	 */
	public function testValidateUnique() {
		$this->AppModel->data = array(
			'AppModel' => array(  // This overlaps an existing AppModel fixture record.
				'id' => '52e00e3e-0210-41ce-b2ec-3e95b368309d',
				'username' => 'test@localhost.com',
			)
		);
		$check = array('email' => 'test@localhost.com');

		$this->assertTrue($this->AppModel->validateUnique($check, true));
		$this->assertFalse($this->AppModel->validateUnique($check, false));
	}

	/**
	 * Provide [check, expected, message] pairs to `testValidateNull()`.
	 *
	 * @return array
	 */
	public function provideValidateNullChecks() {
		return array(
			'null-neg-one' => array(
				array('dummy' => -1),
				false,
				'Integer negative one should not count as NULL.',
			),
			'null-zero' => array(
				array('dummy' => 0),
				false,
				'Integer zero should not count as NULL.',
			),
			'null-one' => array(
				array('dummy' => 1),
				false,
				'Integer one should not count as NULL.',
			),
			'null-string-null' => array(
				array('dummy' => 'null'),
				false,
				'String NULL should not count as NULL.',
			),
			'null-literal-null' => array(
				array('dummy' => null),
				true,
				'Literal NULL should count as NULL.',
			),
		);
	}

	/**
	 * testValidateNull method
	 *
	 * @dataProvider provideValidateNullChecks
	 * @covers AppModel::validateNull
	 * @param	array	$check	A single array containing a [field_name => value] pair to provide to `validateNull()`.
	 * @param	mixed	$expected	The expected value to be returned from `validateNull()`.
	 * @param	string	$msg	The optional PHPUnit error message if the assertion fails.
	 * @return void
	 */
	public function testValidateNull($check, $expected, $msg = '') {
		$this->assertEquals($expected, $this->AppModel->validateNull($check), $msg);
	}

	/**
	 * test emailFactory without passing a config
	 *
	 * @return void
	 */
	public function testEmailFactoryNoConfigPassed() {
		$Model = new TestAppModel();
		$this->assertInstanceOf("AppEmail", $Model->emailFactory());
	}

	/**
	 * test emailFactory with passing a config
	 *
	 * @return void
	 */
	public function testEmailFactoryConfigPassed() {
		Configure::write('Email.Transports.default.from', 'phpunit@loadsys.com');
		$Model = new TestAppModel();
		$this->assertInstanceOf("AppEmail", $Model->emailFactory('default'));
	}
}
