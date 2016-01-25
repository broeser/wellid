<?php

namespace Wellid\Validator;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-01-21 at 15:31:03.
 */
class EmailTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Email
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new Email;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * Email provider, provides email adresses and whether they should be valid
     * 
     * @return array[]
     */
    public function emailProvider() {
        return array(
            array('@example.org', false),
            array('example@', false),
            array('@@@', false),
            array('example', false),
            array(false, false),
            array(null, false),
            array(42, false),
            array('mail@benedictroeser.de', true),
            array('valid.mail@example.org', true),
            array('f@example.info', true)
        );
    }

    /**
     * @covers Wellid\Validator\Email::validate
     * @dataProvider emailProvider
     * @param mixed $value
     * @param boolean $expected
     */
    public function testValidate($value, $expected) {
        $result = $this->object->validate($value);
        
        $this->assertInstanceOf('Wellid\ValidationResult', $result);
                
        if($expected) {
            $this->assertTrue($result->hasPassed());
            $this->assertFalse($result->isError());
            $this->assertEmpty($result->getMessage());
            $this->assertEquals(\Wellid\ValidationResult::ERR_NONE, $result->getCode());
            $this->assertEquals('passed', (string)$result);
        } else {
            $this->assertFalse($result->hasPassed());
            $this->assertTrue($result->isError());
            $this->assertNotEmpty($result->getMessage());
            $this->assertNotEquals(\Wellid\ValidationResult::ERR_NONE, $result->getCode());
            $this->assertNotEquals('passed', (string)$result);
        }
    }

    /**
     * @covers Wellid\Validator\Email::validateBool
     * @dataProvider emailProvider
     * @param mixed $email
     * @param boolean $expected
     */
    public function testValidateBool($email, $expected) {
        $this->assertEquals($expected, $this->object->validateBool($email));
    }

}
