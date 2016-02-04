<?php

namespace Wellid\Validator;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-01-21 at 15:31:03.
 */
class IntegerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Integer
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new Integer;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers Wellid\Validator\Integer::validate
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
     * Dataprovider for testValidate and testValidateBool
     * 
     * @return array()
     */
    public function dataProvider() {
        return array(
            array(null, false),
            array('TRUE', false),
            array('abc', false),
            array(1, true),
            array(1.0, true),
            array(1.1, false),
            array(true, true),
            array(0.1, false),
            array(0.999, false),
            array(434, true),
            array('3', true),
            array('0', true),
            array(0, true),
            array('3.3', false),
            array('', false)
        );
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
     * @covers Wellid\Validator\Integer::validateBool
     * @dataProvider dataProvider
     * @param mixed $value
     * @param boolean $expected
     */
    public function testValidateBool($value, $expected) {
        $this->assertEquals($expected, $this->object->validateBool($value));
    }
}
