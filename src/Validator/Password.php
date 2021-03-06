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
use Wellid\Exception\DataFormat;

/**
 * Description of Password
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class Password implements ValidatorInterface {
    use ValidatorTrait;
    
    /**
     * Hashed password
     * 
     * @var string
     */
    protected $hash;
    
    /**
     * Constructor
     * 
     * @param string $hash
     */
    public function __construct($hash) {
        if(!is_string($hash)) {
            throw new DataType('hash', 'string', $hash);
        }
        
        $hashInfos = password_get_info($hash);
        if($hashInfos['algo']===0) {
            throw new DataFormat('hash', 'a valid password hash', $hash);
        }        
        
        $this->hash = $hash;
    }
    
    /**
     * Validates the given $value
     * Checks if it is a valid password
     * 
     * @param string $value
     * @return ValidationResult
     */
    public function validate($value) {
        if(!is_string($value)) {
            throw new DataType('value', 'string', $value);
        }
        
        if(!password_verify($value, $this->hash)) {
            return new ValidationResult(false, 'Wrong password');
        }        
        return new ValidationResult(true);
    }
}
