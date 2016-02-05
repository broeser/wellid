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

/**
 * Interface for everything that can be assigned Validators.
 * 
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
interface ValidatorHolderInterface {
    /**
     * Assigns a Validator that shall be used to validate this
     * 
     * @param Validator\ValidatorInterface $validator
     * @return ValidatorHolderInterface Returns itself for daisy-chaining
     */
    public function addValidator(Validator\ValidatorInterface $validator);
        
    /**
     * Assigns several Validators that shall be used to validate this
     * 
     * @param Validator\ValidatorInterface ...$validators
     * @return ValidatorHolderInterface Returns itself for daisy-chaining
     */
    public function addValidators(Validator\ValidatorInterface  ...$validators);
    
    /**
     * Returns an array of Validators used to validate this
     * 
     * @return Validator\ValidatorInterface[]
     */
    public function getValidators();
    
    /**
     * Validates a value against all Validators assigned to this ValidatorHolder
     * 
     * @param mixed $value
     * @return ValidationResultSet
     */
    public function validateValue($value);
}
