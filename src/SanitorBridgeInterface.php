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
 * Interface for data objects that shall be used with both Sanitor and wellid
 * 
 * Sanitor is a wrapper around PHP's filter_-functions and can be used to 
 * sanitize user input or other data.
 * 
 * Refer to README.md for usage instructions!
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
interface SanitorBridgeInterface extends \Sanitor\SanitizableInterface, Internal\InternalInterface {
    /**
     * Returns the Sanitizer
     * 
     * @return \Sanitor\Sanitizer
     */
    public function getSanitizer();
    
    /**
     * Sets the Sanitizer
     * 
     * @param \Sanitor\Sanitizer $sanitizer
     */
    public function setSanitizer(\Sanitor\Sanitizer $sanitizer);
        
    /**
     * Obtains a raw value from GET/POST/COOKIE/… and saves it into $this->rawValue
     * 
     * @param int $type INPUT_POST, INPUT_GET, INPUT_COOKIE, INPUT_SERVER, INPUT_ENV, INPUT_REQUEST or INPUT_SESSION
     * @param string $variableName
     */
    public function rawValueFromInput($type, $variableName);
    
    /**
     * Sets the raw value
     * 
     * @param mixed $rawValue
     */
    public function setRawValue($rawValue);
}