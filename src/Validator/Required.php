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
namespace Wellid\Validator;
use Wellid\ValidationResult;
use Wellid\Exception\DataType;
/**
 * Checks if a value is given
 * 
 * The data type of the value has to be specified beforehand, however the validator
 * will not check whether the value corresponds to the data type, use one of the
 * other validators for that
 * 
 * The validator works like this
 * - Integer 0 or float 0.0 will pass (this differs from PHPs empty())
 * - empty arrays will fail
 * - empty strings will fail
 * - NULL will always fail
 * - boolean false will pass
 * - supplying an empty string as value and setting the data type to numeric will fail
 * - supplying the string "123" as value and setting the data type to numeric will pass
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class Required implements ValidatorInterface {
    use ValidatorTrait;
    
    /**
     * Data type of the given value
     * 
     * @var string
     */
    protected $dataType;
    
    const ERR_NULL = 2;
    const ERR_EMPTY_STRING = 4;
    const ERR_EMPTY_NUMERIC = 8;
    const ERR_EMPTY_ARRAY = 16;
    const ERR_FILE = 32;
    
    /**
     * Returns the supported data types
     * 
     * @return array
     */
    private function supportedDataTypes() {
        return array('boolean', 'string', 'array', 'numeric', 'file', 'int', 'float');
    }
    
    /**
     * Constructor
     * 
     * @param string $dataType
     * @throws DataType
     */
    public function __construct($dataType = 'string') {
        $dataType = strtolower($dataType);
        
        if(!in_array($dataType, $this->supportedDataTypes())) {
            throw new DataType('dataType', 'one of '.implode(', ', $this->supportedDataTypes()), $dataType);
        }
        
        $this->dataType = $dataType;
    }
    
    /**
     * Validates the given $value
     * Checks if it is set
     * 
     * @param mixed $value
     * @return ValidationResult
     */
    public function validate($value) {
        if(is_null($value)) { 
            return new ValidationResult(false, 'Null', self::ERR_NULL);
        }
        
        switch($this->dataType) {
            case 'string':
                if(strlen($value)===0) {
                    return new ValidationResult(false, 'Empty', self::ERR_EMPTY_STRING);
                }
            break;
            case 'array':
                if(empty($value)) {
                    return new ValidationResult(false, 'Empty', self::ERR_EMPTY_ARRAY);
                }
            break;
            case 'numeric':
            case 'int':
            case 'float':
                if(!is_numeric($value) && strlen($value)===0) {
                    return new ValidationResult(false, 'Empty', self::ERR_EMPTY_NUMERIC);
                }
            break;
            case 'file':
                if(!is_array($value) || !isset($value['tmp_name']) || !isset($value['size']) || !isset($value['error'])) {
                    return new ValidationResult(false, 'Required file upload information is missing', self::ERR_FILE);
                }
            break;
        }

        return new ValidationResult(true);
    }
}
