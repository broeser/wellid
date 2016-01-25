<?php

namespace WellidUsageExamples;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-01-24 at 19:20:33.
 */
class AccountBalanceTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var AccountBalance
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new AccountBalance;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers WellidUsageExamples\AccountBalance::createFromFloat
     */
    public function testCreateFromFloat() {
        $floatValue = -0.076;
        $obj = AccountBalance::createFromFloat($floatValue);
        $this->assertInstanceOf('WellidUsageExamples\AccountBalance', $obj);
        $this->assertInternalType('float', $obj->getValue());
        $this->assertEquals($floatValue, $obj->getValue());
        $this->assertGreaterThan(0, $obj->getValidators());
    }

    /**
     * @covers WellidUsageExamples\AccountBalance::getValue
     */
    public function testGetValue() {
        $this->assertNull($this->object->getValue());
    }

    /**
     * @covers WellidUsageExamples\AccountBalance::setValue
     */
    public function testSetValue() {
        $this->object->setValue(5);
        $this->assertEquals(5, $this->object->getValue());
        $this->object->setValue('foo');
        $this->assertEquals('foo', $this->object->getValue());
    }

    /**
     * Data provider for the validate and validateBool-methods()
     * 
     * @return array
     */
    public function dataProvider() {
        return array(
            57.3 => true,
            -6 => false,
            'foo' => false
        );
    }
    
    /**
     * Helper to check whether a ValidationResultSet is correct
     * 
     * @param \Wellid\ValidationResultSet $result
     * @param boolean $expected
     */
    private function checkResult(\Wellid\ValidationResultSet $result, $expected) {
        $this->assertInstanceOf('Wellid\ValidationResultSet', $result);
        $this->assertEquals(count($this->object->getValidators()), count($result));
        
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
     * @covers WellidUsageExamples\AccountBalance::validate
     * @dataProvider dataProvider
     * @depends testSetValue
     * @param mixed $value
     * @param boolean $expected
     */
    public function testValidate($value, $expected) {
        $this->object->setValue($value);
        
        $result = $this->object->validate();
        
        $this->checkResult($result, $expected);
    }

    /**
     * @covers WellidUsageExamples\AccountBalance::validateBool
     * @dataProvider dataProvider
     * @param mixed $value
     * @param boolean $expected
     * @depends testSetValue
     */
    public function testValidateBool($value, $expected) {
        $this->object->setValue($value);
        $this->assertEquals($expected, $this->object->validateBool());
    }

    /**
     * @covers WellidUsageExamples\AccountBalance::validateValue
     * @dataProvider dataProvider
     * @depends testSetValue
     * @param mixed $value
     * @param boolean $expected
     */
    public function testValidateValue($value, $expected) {
        $result = $this->object->validateValue($value);
        
        $this->checkResult($result, $expected);
    }

    /**
     * @covers Wellid\ValidatorHolder::addValidator
     * @covers Wellid\ValidatorHolder::getValidators
     */
    public function testAddValidator() {
        $this->assertEmpty($this->object->getValidators());

        $validator = new Validator\Boolean();

        $this->assertInstanceOf('Wellid\ValidatorHolderInterface', $this->object->addValidator($validator));

        $this->assertCount(1, $this->object->getValidators());

        $this->assertContainsOnlyInstancesOf(get_class($validator), $this->object->getValidators());
    }

    /**
     * @covers Wellid\ValidatorHolder::addValidators
     * @depends testAddValidator
     */
    public function testAddValidators() {
        $this->assertEmpty($this->object->getValidators());

        $this->assertInstanceOf('Wellid\ValidatorHolderInterface', $this->object->addValidators(new Validator\Boolean(), new Validator\MIME('text/plain'), new Validator\MinLength(3)));

        $this->assertCount(3, $this->object->getValidators());

        $this->assertContainsOnlyInstancesOf('Wellid\Validator\ValidatorInterface', $this->object->getValidators());
    }

}
