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
 * Description of Min
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class Min implements ValidatorInterface {
    use ValidatorTrait;
    
    /**
     * Minimum
     * 
     * @var float|int
     */
    protected $min;
    
    /**
     * Constructor
     * 
     * @param float|int $min Minimum
     */
    public function __construct($min) {
        if(!is_numeric($min)) {
            throw new DataType('min', 'number', $min);
        }        
        
        $this->min = $min;
    }
    
    /**
     * Validates the given $value
     * Checks if it is higher then the minimum
     * 
     * @param mixed $value
     * @return ValidationResult
     */
    public function validate($value) {
        if(!is_numeric($value)) {
            throw new DataType('value', 'number', $value);
        }
                
        if($value < $this->min) {
            return new ValidationResult(false, sprintf('Value has to be above %d', $this->min));
        }        
        
        return new ValidationResult(true);
    }
}
