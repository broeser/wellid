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

use Wellid\Exception\DataType;

/**
 * Result of a single validation is put into a ValidationResult containing
 * the "passed"-status (yes/no) and the error message if validation has not
 * passed
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class ValidationResult {
    /**
     * @var boolean Whether or not the validation was successful
     */
    protected $passed;
    
    /**
     * Error message
     * 
     * @var string 
     */
    protected $errorMessage;
    
    /**
     * Error code, as defined in the validator classes
     * Error codes don't have to be unique between different validator classes
     * 
     * @var int
     */
    protected $errorCode;
    
    /**
     * No error
     */
    const ERR_NONE = 0;
    
    /**
     * Used in case there is no specific error code defined by a validator
     */
    const ERR_DEFAULT = 1;
    
    /**
     * Constructor
     * 
     * @param boolean $passed Whether validation passed
     * @param string $errorMessage Error message
     * @param int $errorCode Error code
     * @throws exceptions\DataType
     */
    public function __construct($passed, $errorMessage = '', $errorCode = self::ERR_DEFAULT) {
        if(!is_bool($passed)) {
            throw new DataType('passed', 'boolean', $passed);
        }
        
        if($passed) {
            $errorCode = self::ERR_NONE;
        }
        
        $this->passed = $passed;
        $this->errorMessage = $errorMessage;
        $this->errorCode = $errorCode;
    }
    
    /**
     * Returns a string representation of this ValidationResult
     * 
     * @return string
     */
    public function __toString() {
        if($this->passed) {
            return 'passed';
        }
        
        return $this->errorMessage;
    }
    
    /**
     * Returns whether validation has resulted in an error (invalid value)
     * 
     * @return boolean
     */
    public function isError() {      
        return !$this->passed;
    }
    
    /**
     * Returns whether validation has passed (valid value)
     * 
     * @return boolean
     */
    public function hasPassed() {
        return $this->passed;
    }
    
    /**
     * Returns the error message
     * 
     * @return string
     */
    public function getMessage() {
        return $this->errorMessage;
    }
    
    /**
     * Returns the error code
     * 
     * @return int
     */
    public function getCode() {
        return $this->errorCode;
    }
}
