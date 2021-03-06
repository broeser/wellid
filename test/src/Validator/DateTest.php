<?php

namespace Wellid\Validator;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-01-21 at 15:31:03.
 */
class DateTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Date
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new Date;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * Date provider, provides a date and whether it should be valid
     * 
     * @return array[]
     */
    public function dateProvider() {
        return array(
            array('1970-13-01', false),
            array('3000-02-30', false),
            array('abcd-ef-gh', false),
            array(false, false),
            array(null, false),
            array(42, false),
            array('1910-11-01', true),
            array('2016-02-29', true),
            array('3008-10-19', true)
        );
    }

    /**
     * @covers Wellid\Validator\Date::validate
     * @dataProvider dateProvider
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
     * @covers Wellid\Validator\Date::validateBool
     * @dataProvider dateProvider
     * @param mixed $date
     * @param boolean $expected
     */
    public function testValidateBool($date, $expected) {
        $this->assertEquals($expected, $this->object->validateBool($date));
    }

    /**
     * @covers Wellid\Validator\Date::__construct
     */
    public function testConstructor() {
        $d = new Date('d.m.Y');
        $this->assertTrue($d->validateBool('30.11.2011'));
        
        try {
            new Date(123);
        } catch (\Wellid\Exception\DataType $ex) {
            return;
        }
        $this->fail('Expected DataType-Exception was not thrown');
    }
}
