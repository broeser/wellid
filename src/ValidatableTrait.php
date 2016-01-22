<?php
/*
 * The MIT License
 *
 * Copyright 2016 Benedict Roeser <b-roeser@gmx.net>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Wellid;
use Wellid\Validator\ValidatorInterface;
/**
 * This trait can be used in everything that can be validated
 * Can be used on value objects (e. g. "Money") or on objects that hold several 
 * values (e. g. "Form")
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
trait ValidatableTrait {
    /**
     * Validators with which this will be validated
	 * 
	 * @var ValidatorInterface[]
     */
    protected $validators = array();
	    
    /**
     * ValidationResultSet of the last validation of this
     * 
     * @var ValidationResultSet
     */
    protected $lastValidationResult = null;
    
    /**
     * Validates this against all given Validators
     * 
     * @return ValidationResultSet
     */
    public function validate() {        
        if($this->lastValidationResult instanceof ValidationResultSet) {
            return $this->lastValidationResult;
        }
                
        $this->lastValidationResult = $this->validateValue($this->getValue());
            
        return $this->lastValidationResult;
    }
    
    /**
     * Validates a value against all given Validators
     * - for internal use -
     * 
     * @param mixed $value
     * @return ValidationResultSet
     */
    protected function validateValue($value) {
        $validationResultSet = new ValidationResultSet();
        foreach($this->validators as $validator) {
            $validationResultSet->add($validator->validate($value));
        }
        return $validationResultSet;
    }
    
    /**
     * Removes the last ValidationResultSet from cache in order to re-validate 
     * this (usually not necessary)
     */
    public function clearValidationResult() {
        $this->lastValidationResult = null;
    }
    
    /**
     * Adds a Validator to this
     * 
     * @param ValidatorInterface $validator
     * @return ValidatableInterface Returns itself for daisy-chaining
     */
    public function addValidator(ValidatorInterface $validator) {
        $this->validators[get_class($validator)] = $validator;
        
        return $this;
    }
    
    /**
     * Assigns several Validators that shall be used to validate this
     * 
     * @param ValidatorInterface ...$validators
     */
    public function addValidators(ValidatorInterface ...$validators) {
        foreach($validators as $validator) {
            $this->addValidator($validator);
        }
    }
    
    /**
     * Returns an array of Validators used to validate this Field
     * 
     * @return ValidatorInterface[]
     */
    public function getValidators() {
        return $this->validators;
    }
}