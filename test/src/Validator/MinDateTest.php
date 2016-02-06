<?php

namespace Wellid\Validator;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-02-06 at 14:53:14.
 */
class MinDateTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var MinDate
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new MinDate('2011-01-02');
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
            array('1970-12-01', false),
            array('abcdef', false),
            array('3000-02-30', false),
            array('3000-02-20', true),
            array('1910-11-01', false),
            array('2016-02-29', true)
        );
    }

    /**
     * @covers Wellid\Validator\MinDate::validate
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
     * @covers Wellid\Validator\MinDate::validateBool
     * @dataProvider dateProvider
     * @param mixed $date
     * @param boolean $expected
     */
    public function testValidateBool($date, $expected) {
        $this->assertEquals($expected, $this->object->validateBool($date));
    }
    
    /**
     * @covers Wellid\Validator\MinDate::__construct
     */
    public function testConstructor() {
        $exceptionOkay = false;
        try {
            $v = new MinDate('abcd-ab-ab');    
        } catch (\Wellid\Exception\DataFormat $ex) {
            $exceptionOkay = true;
        }
        
        if(!$exceptionOkay) {
            $this->fail('Expected DataFormat Exception not thrown');
        }
        $exceptionOkay = false;
        try {
            $v = new MinDate('2011', 42);    
        } catch (\Wellid\Exception\DataType $ex) {
            $exceptionOkay = true;
        }
        
        if(!$exceptionOkay) {
            $this->fail('Expected DataType Exception not thrown');
        }
        
        $v = new MinDate(new \DateTimeImmutable('now'), 'dmY');
        $d = new \DateTimeImmutable('now +7 days');
        $this->assertTrue($v->validateBool($d->format('dmY')));
        $this->assertFalse($v->validateBool($d->format('d-m-Y')));
    }

}
