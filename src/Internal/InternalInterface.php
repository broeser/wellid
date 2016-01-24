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
 * @internal This interface is used by the ValidatableInterface and SanitorBridgeInterface
 *           to reduce code duplication. By design, wellid does not suggest the
 *           use of this interface directly but proposes to use either 
 *           ValidatableInterface or SanitorBridgeInterface. In case you want to use 
 *           this functionality for validatable data objects without the 
 *           FieldHolderInterface, feel free to do so, but be advised, that no
 *           documentation on this topic exists.
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
interface InternalInterface {
    /**
     * Validates this against all assigned Validators
     * 
     * @return ValidationResultSet
     */
    public function validate();
    
    /**
     * Validates this against all assigned Validators
     * 
     * Returns true if everything passed, false if there was at least one error.
     * 
     * @return boolean
     */
    public function validateBool();
    
    /**
     * Returns the value that shall be passed to the assigned validators
     * 
     * @return mixed
     */
    public function getValue();
}