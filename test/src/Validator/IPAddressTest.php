<?php

namespace Wellid\Validator;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-01-24 at 16:30:18.
 */
class IPAddressTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var IPAddress
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new IPAddress;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

	/**
     * Dataprovider for testValidate and testValidateBool
     * 
     * @return array()
     */
    public function dataProvider() {
        return array(
            array(null, false),
            array('TRUE', false),
            array('abc', false),
            array(1, false),
            array('123.123.123.123', true),
			array('2001:0db8:85a3:08d3:1319:8a2e:0370:7344', true),
			array('2001:ghij:85a3:08d3:1319:8a2e:0370:7344', false),
            array('123.456.789.123', false),
			array('123.121.12.121', true),
			array('123.1121.12.121', false),
            array('', false)
        );
    }	
	
    /**
     * @covers Wellid\Validator\IPAddress::validate
     * @dataProvider dataProvider
     * @param mixed $value
     * @param boolean $expected
     */
    public function testValidate($value, $expected) {
        $result = $this->object->validate($value);

        $this->assertInstanceOf('Wellid\ValidationResult', $result);

        if ($expected) {
            $this->assertTrue($result->hasPassed());
            $this->assertFalse($result->isError());
            $this->assertEmpty($result->getMessage());
            $this->assertEquals(\Wellid\ValidationResult::ERR_NONE, $result->getCode());
            $this->assertEquals('passed', (string) $result);
        } else {
            $this->assertFalse($result->hasPassed());
            $this->assertTrue($result->isError());
            $this->assertNotEmpty($result->getMessage());
            $this->assertNotEquals(\Wellid\ValidationResult::ERR_NONE, $result->getCode());
            $this->assertNotEquals('passed', (string) $result);
        }
    }

    /**
     * 
     */
    public function testArrayAndObjectValidation() {
        $this->assertFalse($this->object->validateBool(array(true)));
        $x = new \stdClass();
        $x->y = 'z';
        $this->assertFalse($this->object->validateBool($x));
    }
	
	/**
     * @covers Wellid\Validator\IPAddress::__construct
     */
	public function testExceptions() {
		try {
			new IPAddress('FILTER_FLAG_IPV4');
		} catch (\Wellid\Exception\DataType $ex) {
			return;
		}
		$this->fail('Expected exception was not thrown.');
	}

    /**
     * @covers Wellid\Validator\IPAddress::validateBool
     * @dataProvider dataProvider
     * @param mixed $value
     * @param boolean $expected
     */
    public function testValidateBool($value, $expected) {
        $this->assertEquals($expected, $this->object->validateBool($value));
    }

}