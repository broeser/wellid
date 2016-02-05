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
 * Checks the file size of a file, takes a filename as input
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class Filesize implements ValidatorInterface {
    use ValidatorTrait;
    
    /**
     * Maximum filesize
     * 
     * @var int
     */
    protected $max;
    
    /**
     * Constructor
     *
     * @param int $max Maximum filesize in bytes
     */
    public function __construct($max) {
        if(!is_int($max) || $max < 0) {
            throw new DataType('max', 'positive integer', $max);
        }
        
        $this->max = $max;
    }
    
    /**
     * Validates the given $value
     * Checks if the file it points to has a valid size
     * 
     * @param string $value Filename
     * @return ValidationResult
     */
    public function validate($value) {
        if(!is_string($value)) {
            throw new DataType('value', 'string', $value);
        }
        
        try {
            $filesize = @filesize($value);            
        } catch (Exception $ex) {
            $filesize = false;
        }

        if($filesize===false) {
            throw new \Wellid\Exception\FileNotFound($value, 'file for Filesize validator');
        }
        
        if($filesize>$this->max) {
            return new ValidationResult(false, 'File is too large');
        }

        return new ValidationResult(true);
    }
}
