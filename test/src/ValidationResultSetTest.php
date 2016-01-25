<?php

namespace Wellid;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-01-21 at 15:31:04.
 */
class ValidationResultSetTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var ValidationResultSet
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new ValidationResultSet();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers Wellid\ValidationResultSet::hasErrors
     * @todo   Implement testHasErrors().
     */
    public function testHasErrors() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Wellid\ValidationResultSet::hasPassed
     * @todo   Implement testHasPassed().
     */
    public function testHasPassed() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Wellid\ValidationResultSet::add
     * @todo   Implement testAdd().
     */
    public function testAdd() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Wellid\ValidationResultSet::addSet
     */
    public function testAddSet() {
        $this->object->addSet(new ValidationResultSet());
        $this->assertCount(0, $this->object->count());
        
        $set = new ValidationResultSet();
        $set->add(new ValidationResult(true));
        $set->add(new ValidationResult(true));
        $this->object->addSet($set);
        $this->assertTrue($this->object->hasPassed());
        $this->assertCount(2, $this->object->count());
        
        $set2 = new ValidationResultSet();
        $set2->add(new ValidationResult(false, 'test error message'));
        $set2->add(new ValidationResult(true));
        $this->object->addSet($set2);
        $this->assertFalse($this->object->hasPassed());
        $this->assertCount(4, $this->object->count());
        
        $set3 = new ValidationResultSet();
        $set3->add(new ValidationResult(false, 'another message'));
        $this->object->addSet($set3);
        $this->assertEquals('test error message', $this->object->firstError()->getMessage());
    }

    /**
     * @covers Wellid\ValidationResultSet::rewind
     * @covers Wellid\ValidationResultSet::next
     * @covers Wellid\ValidationResultSet::key
     * @covers Wellid\ValidationResultSet::current
     * @covers Wellid\ValidationResultSet::valid
     * @todo   Implement testForeach().
     */
    public function testForeach() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Wellid\ValidationResultSet::count
     * @depends testAdd
     * @todo   Implement testCount().
     */
    public function testCount() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

}
