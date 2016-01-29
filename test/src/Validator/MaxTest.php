<?php

namespace Wellid\Validator;
use Wellid\Exception;
/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-01-21 at 15:31:02.
 */
class MaxTest extends \PHPUnit_Framework_TestCase {
    const EXPECT_EXCEPTION = 'x';
    /**
     * @var Max
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new Max(5.8);
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
            array(null, self::EXPECT_EXCEPTION),
            array('TRUE', self::EXPECT_EXCEPTION),
            array('abc', self::EXPECT_EXCEPTION),
            array(1, true),
            array(1.0, true),
            array(1.1, true),
            array(true, self::EXPECT_EXCEPTION),
            array(-0.999, true),
            array(-434, true),
            array(5.9, false),
            array(58, false),
            array(5.8, true),
            array('3', self::EXPECT_EXCEPTION),
            array('3.3', self::EXPECT_EXCEPTION),
            array('', self::EXPECT_EXCEPTION)
        );
    }
    
    /**
     * @covers Wellid\Validator\Max::validate
     * @dataProvider dataProvider
     * @param mixed $value
     * @param boolean $expected
     */
    public function testValidate($value, $expected) {
        try {
            $result = $this->object->validate($value);
        } catch (Exception\DataType $ex) {
            if ($expected !== self::EXPECT_EXCEPTION) {
                throw $ex;
            } else {
                return;
            }
        }

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
     * @covers Wellid\Validator\Min::validateBool
     */
    public function testValidateBool() {
        $this->assertFalse($this->object->validateBool(7));
        $this->assertTrue($this->object->validateBool(5.8));
        $this->assertTrue($this->object->validateBool(0));
    }

}
