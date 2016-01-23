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
 * This trait allows to easily pass values from Sanitor to wellid
 * 
 * Sanitor is a wrapper around PHP's filter_-functions and can be used to 
 * sanitize user input or other data.
 * 
 * If you want to use Sanitor in conjunction with wellid, follow these four 
 * steps:
 * 1. install Sanitor (composer require broeser/sanitor)
 * 2. use this trait in alle places where you'd otherwise use ValidatableTrait
 * 3. make sure that your classes implementing ValidatableInterface also 
 *    implement \Sanitor\SanitizableInterface
 * 4. make sure that these classes call $this->setSanitizer(...) somwhere
 *    before validation (e. g. in the constructor) and set a fitting
 *    sanitization filter (you can try FILTER_DEFAULT)
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
trait SanitorBridgeTrait {
    use \Sanitor\SanitizableTrait;
    
    /**
     * Unsafe unsanitized unfiltered unvalidated raw value
     * 
     * @var mixed
     */
    private $rawValue;
    
    /**
     * The Sanitizer used to filter the value of this Field
     * 
     * @var \Sanitor\Sanitizer
     */
    protected $sanitizer;
    
    /**
     * Returns the Sanitizer
     * 
     * @return \Sanitor\Sanitizer
     */
    public function getSanitizer() {
        return $this->sanitizer;
    }
    
    /**
     * Sets the Sanitizer
     * 
     * @param \Sanitor\Sanitizer $sanitizer
     */
    public function setSanitizer(\Sanitor\Sanitizer $sanitizer) {
        $this->sanitizer = $sanitizer;
    }

    /**
     * Alias for getFilteredValue(), expected by wellid's ValidatableInterface
     * 
     * @return mixed
     */
    public function getValue() {
        return $this->getFilteredValue();
    }
    
    /**
     * Obtains a raw value from GET/POST/COOKIE/… and saves it into $this->rawValue
     * 
     * @param int $type INPUT_POST, INPUT_GET, INPUT_COOKIE, INPUT_SERVER, INPUT_ENV, INPUT_REQUEST or INPUT_SESSION
     * @param string $variableName
     */
    public function rawValueFromInput($type, $variableName) {
        $this->rawValue = null;
        
        if(!$this->getSanitizer()->filterHas($type, $variableName)) {
            return;
        }
        
        switch($type) {
            case INPUT_COOKIE:
            case INPUT_ENV:
            case INPUT_GET:
            case INPUT_POST:
            case INPUT_SERVER:
                $this->setRawValue(filter_input($type, $variableName, FILTER_UNSAFE_RAW));
            case INPUT_REQUEST:
                $this->setRawValue($_REQUEST[$variableName]);
            case INPUT_SESSION:
                $this->setRawValue($_SESSION[$variableName]);
        }
    }
    
    /**
     * Sets the raw value
     * 
     * @param mixed $rawValue
     */
    public function setRawValue($rawValue) {
        $this->rawValue = $rawValue;
    }
    
    /**
     * Returns the raw value
     * 
     * !!! Do not output or store this raw value anywhere !!!
     * 
     * @return mixed
     */
    public function getRawValue() {
        return $this->rawValue;
    }    
}
