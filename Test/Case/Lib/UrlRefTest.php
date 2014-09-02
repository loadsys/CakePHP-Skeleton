<?php
App::uses('UrlRef', 'Lib');

/**
 * Sample class that implements the required `::buildUrl()` method. Can be
 * mocked to override the method if necessary. This class is not a Controller,
 * but should still match the literal class name 'UrlRefBlogSample'
 *
 */
class UrlRefBlogSample {
	public function buildUrl($id, $options) {
		if (in_array('full', $options)) {
			return sprintf('http://localhost/blog/show/%s', $id);
		} else {
			return sprintf('/blog/show/%s', $id);
		}
	}
}

/**
 * Sample class for testing inflection matching.
 *
 */
class TestPeopleController {
	public function buildUrl($id, $options) {
		return sprintf('/test_people/details/%s', $id);
	}
}

/**
 * Sample class that does not implement the required `::buildUrl()` method.
 * Used for testing the built-in generic URL construction.
 *
 */
class UrlRefNoBuildUrl {
}


/**
 * UrlRef Test Case
 */
class UrlRefTest extends CakeTestCase {

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * Provide a full range of sample strings containing valid markers and
	 * their converted counterparts (using the `UrlRefBlogSample` class
	 * defined above).
	 *
	 * @return array	Sets of ['input', 'expected', 'msg'] values.
	 */
	public function provideMarkerStringsAndResults() {
		return array(
			// Class contains a `buildUrl()` method.
			array(
				'{{UrlRefBlogSample:1}}',
				'/blog/show/1',
			),

			// Class has no `buildUrl() method`, should silently fail.
			array(
				'{{UrlRefNoBuildUrl:2}}',
				'{{UrlRefNoBuildUrl:2}}',
			),

			// Multiple matches in the string.
			array(
				'First: {{UrlRefBlogSample:1:full}} Second: {{UrlRefNoBuildUrl:3:full}} Not a marker without an id: {{UrlRefBlogSample}}' . PHP_EOL . 'Third: {{UrlRefBlogSample:4}}',
				'First: http://localhost/blog/show/1 Second: {{UrlRefNoBuildUrl:3:full}} Not a marker without an id: {{UrlRefBlogSample}}' . PHP_EOL . 'Third: /blog/show/4',
			),
		);
	}

	/**
	 * Provide arguments and expected results for UrlRef::get().
	 *
	 * @return array	Sets of [Class, id, [options], expected, assert_msg] values.
	 */
	public function provideGetArgsAndResults() {
		return array(
			// Literal class with a `buildUrl()` method. (Should return formatted URLs.)
			array(
				'UrlRefBlogSample',
				1,
				array(),
				'/blog/show/1',
				'Literal class name, has buildUrl, no options.',
			),
			array(
				'UrlRefBlogSample',
				2,
				array('full'),
				'http://localhost/blog/show/2',
				'Literal class name, has buildUrl, full URL option.',
			),

			// Literal class without a `buildUrl()` method. (Should return false.)
			array(
				'UrlRefNoBuildUrl',
				3,
				array(),
				false,
				'Literal class name, no buildUrl, no options.',
			),

			// Full controller class with a `buildUrl()` method. (Should return formatted URLs.)
			array(
				'TestPeopleController',
				4,
				array(),
				'/test_people/details/4',
				'Full class name, has buildUrl, no options.',
			),

			// Abbreviated controller class with a `buildUrl()` method. (Should return formatted URLs.)
			array(
				'TestPeople',
				5,
				array(),
				'/test_people/details/5',
				'Abbreviated class name, has buildUrl, no options.',
			),

			// Inflected and abbreviated controller class with a `buildUrl()` method. (Should return formatted URLs.)
			array(
				'TestPerson',
				6,
				array(),
				'/test_people/details/6',
				'Inflected and abbreviated class name, has buildUrl, no options.',
			),

			// Poorly Camelcased and abbreviated controller class with a `buildUrl()` method. (Should return formatted URLs, but only because PHP class names are not case-sensitive.)
			array(
				'testperson',
				7,
				array(),
				'/test_people/details/7',
				'Poorly camelcased and abbreviated class name, has buildUrl, no options.',
			),

			// Non-existant class. (Should return false.)
			array(
				'ClassDoesNotExist',
				8,
				array(),
				false,
				'Non-existant class name.',
			),
		);
	}

	/**
	 * Provide sample strings with markers that may or may not be able to be
	 * replaced, and the expected boolean value from running ::validate() on
	 * them.
	 *
	 * @return array	Sets of ['input', true|false, 'msg'] values.
	 */
	public function provideValidateArgsAndResults() {
		return array(
			// Class contains a `buildUrl()` method.
			array(
				'{{UrlRefBlogSample:1}}',
				true,
				array(),
			),
			array(
				'{{UrlRefBlogSample:1:full}}',
				true,
				array(),
			),

			// Class has no `buildUrl() method`, should silently fail.
			array(
				'{{UrlRefNoBuildUrl:2:full}}',
				false,
				array(),
			),

			// Multiple matches in the string.
			array(
				'First: {{UrlRefBlogSample:1}} Second: {{UrlRefBlogSample:2}}',
				true,
				array(),
			),
			array(
				'First: {{UrlRefBlogSample:2:full}} Second: {{UrlRefNoBuildUrl:3:full}}',
				false,
				array(),
			),

			// Not quite a valid marker
			array(
				'Not a marker without an id: {{UrlRefBlogSample}}',
				true,
				array(),
				'Should pass because there are no valid markers before *OR* after replacement.',
			),

		);
	}

	/**
	 * Confirm that ::parse() can match and replace all expected {{...}} instances.
	 *
	 * @dataProvider provideMarkerStringsAndResults
	 *
	 * @param string	$str		A string containing {{...}} markers.
	 * @param string	$expected	The expected converted string.
	 * @param string	$msg		Optional PHPUnit message if the assertion fails.
	 * @return void
	 */
	public function testParse($input, $expected, $msg = '') {
		$this->assertEquals($expected, UrlRef::parse($input), $msg);
	}

	/**
	 * Confirm that ::get() returns the appropriate results.
	 *
	 * @dataProvider provideGetArgsAndResults
	 *
	 * @param string		$class		The Class name for the object to which a link should be created.
	 * @param integer|uuid	$id			An integer or UUID "ID" of the object for which a URL will be created.
	 * @param array			$options	Optional options to pass to the buildUrl method.
	 * @param string		$expected	The expected converted string.
	 * @param string		$msg		Optional PHPUnit message if the assertion fails.
	 * @return void
	 */
	public function testGet($class, $id, $options, $expected, $msg = '') {
		$this->assertEquals($expected, UrlRef::get($class, $id, $options), $msg);
	}


	/**
	 * Confirm that ::validate() returns the appropriate results.
	 *
	 * @dataProvider provideValidateArgsAndResults
	 *
	 * @param string	$str		A string containing {{...}} markers.
	 * @param boolean	$expected	True or false depending on if the string can be completely matched or not.
	 * @param string	$msg		Optional PHPUnit message if the assertion fails.
	 * @return void
	 */
	public function testValidate($str, $expected, $options = array(), $msg = '') {
		$this->assertEquals($expected, UrlRef::validate($str, $options), $msg);
	}
}
