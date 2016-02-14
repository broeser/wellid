<?php

namespace Wellid\Exception;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-02-07 at 11:36:24.
 */
class NotFoundTest extends \PHPUnit_Framework_TestCase {
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers Wellid\Exception\NotFound::__construct
     */
    public function testNotFound() {
        $e = new NotFound('Internal Key #44', '$INTERNALS_ARRAY', 500);
        $this->assertInstanceOf('\Exception', $e);
        $this->assertEquals(__LINE__-2, $e->getLine());
        $this->assertEquals(__FILE__, $e->getFile());
        $this->assertEquals(500, $e->getCode());
        $this->assertNull($e->getPrevious());
        try {
            throw $e;
        } catch (Exception $ex) {
            $this->assertContains('Internal Key #44', $ex->getMessage());
            $this->assertContains('$INTERNALS_ARRAY', $ex->getMessage());
            return;
        }
        $this->fail('Exception was not thrown');
    }        
}