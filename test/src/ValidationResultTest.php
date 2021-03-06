<?php

namespace Wellid;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-01-21 at 15:31:04.
 */
class ValidationResultTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var ValidationResult
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new ValidationResult(true);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers Wellid\ValidationResult::__toString
     */
    public function test__toString() {
        $this->assertEquals('passed', (string)$this->object);
        $v = new ValidationResult(false, 'ERROR!!!');
        $this->assertEquals('ERROR!!!', (string)$v);
    }

    /**
     * @covers Wellid\ValidationResult::isError
     */
    public function testIsError() {
        $this->assertFalse($this->object->isError());
    }

    /**
     * @covers Wellid\ValidationResult::hasPassed
     */
    public function testHasPassed() {
        $this->assertTrue($this->object->hasPassed());
    }

    /**
     * @covers Wellid\ValidationResult::getMessage
     */
    public function testGetMessage() {
        $this->assertTrue(is_string($this->object->getMessage()));
        $this->assertEmpty($this->object->getMessage());
    }

    /**
     * @covers Wellid\ValidationResult::getCode
     */
    public function testGetCode() {
        $this->assertEquals(ValidationResult::ERR_NONE, $this->object->getCode());
    }
    
    /**
     * @covers Wellid\ValidationResult::__construct
     */
    public function testConstructor() {
        $v = new ValidationResult(true, 'Everything is fine!', ValidationResult::ERR_DEFAULT);
        $this->assertEquals(ValidationResult::ERR_NONE, $v->getCode());
        
        try {
            new ValidationResult('true');
        } catch (Exception\DataType $ex) {
            return;
        }
        $this->fail('Expected DataType-Exception was not thrown');
    }
}
