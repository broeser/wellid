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
 * Description of SanitorMatch
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class SanitorMatch implements ValidatorInterface {
    use ValidatorTrait;
    
    /**
     * \Sanitor\SanitizableInterface $sanitizable
     * 
     * @var type 
     */
    private $sanitizable;
    
    /**
     * Constructor
     * 
     * @param \Sanitor\SanitizableInterface $sanitizable
     */
    public function __construct(\Sanitor\SanitizableInterface $sanitizable) {
        $this->sanitizable = $sanitizable;
    }
    
    /**
     * Validates the given $value
     * Checks if it differs from the unsanitized/unfiltered input
     * 
     * @param string $value
     * @return ValidationResult
     */
    public function validate($value) {
        if($this->sanitizable->getRawValue()===$value) {
            return new ValidationResult(true);
        }
        
        return new ValidationResult(false, 'The given value contains illegal characters');
    }
}
