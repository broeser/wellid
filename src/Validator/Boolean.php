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

/**
 * Description of Boolean
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class Boolean implements ValidatorInterface {
    use ValidatorTrait;
    
    const ERR_OBJECT = 2;
    const ERR_NULL = 4;
    const ERR_NOBOOLEAN = 8;
    
    /**
     * Validates the given $value
     * Checks if it is a valid boolean
     * 
     * @param boolean $value
     * @return ValidationResult
     */
    public function validate($value) {
        // Sadly php bug #67167 makes the following lines necessary
        if(is_null($value)) {
            return new ValidationResult(false, 'Not a valid boolean but NULL', self::ERR_NULL);
        }
        if(is_object($value)) {
            return new ValidationResult(false, 'Not a valid boolean but an object', self::ERR_OBJECT);
        }
        
        if(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)===null) {
            return new ValidationResult(false, 'Not a valid boolean', self::ERR_NOBOOLEAN);
        }        
        return new ValidationResult(true);
    }
}
