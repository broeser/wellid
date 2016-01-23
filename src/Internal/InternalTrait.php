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
namespace Wellid\Internal;

/**
 * @internal This trait is used by the ValidatableTrait and SanitorBridgeTrait
 *           to reduce code duplication. By design, wellid does not suggest the
 *           use of this trait directly but proposes to use either 
 *           ValidatableTrait or SanitorBridgeTrait. In case you want to use 
 *           this functionality for validatable data objects without the 
 *           FieldHolderTrait, feel free to do so, but be advised, that no
 *           documentation on this topic exists.
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
trait InternalTrait {
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
     * Removes the last ValidationResultSet from cache in order to re-validate 
     * this (usually not necessary)
     */
    public function clearValidationResult() {
        $this->lastValidationResult = null;
    }
}