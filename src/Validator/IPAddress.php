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
 * Description of IPAddress
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class IPAddress implements ValidatorInterface {
    use ValidatorTrait;
    
    /**
     * Flags
     * 
     * @var int
     */
    protected $flags;
    
    /**
     * Constructor
     * 
     * @param int $flags Flags
     */
    public function __construct($flags = null) {
        if(!is_null($flags) && !is_int($flags)) {
            throw new DataType('flags', 'integer', $flags);
        }
        
        $this->flags = $flags;
    }
    
    /**
     * Validates the given $value
     * Checks if it is a valid ip address
     * 
     * @param string $value
     * @return ValidationResult
     */
    public function validate($value) {
        if(!filter_var($value, FILTER_VALIDATE_IP, $this->flags)) {
            return new ValidationResult(false, 'Not a valid ip address');
        }        
        return new ValidationResult(true);
    }
}
