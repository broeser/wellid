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
     * @todo   Implement test__toString().
     */
    public function test__toString() {
         // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
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
    }

    /**
     * @covers Wellid\ValidationResult::getCode
     */
    public function testGetCode() {
        $this->assertEquals(ValidationResult::ERR_NONE, $this->object->getCode());
    }

}