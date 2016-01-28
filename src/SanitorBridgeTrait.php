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
 * Refer to README.md for usage instructions!
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
trait SanitorBridgeTrait {
    use \Sanitor\SanitizableTrait, CacheableValidatableTrait;
    
    /**
     * Unsafe unsanitized unfiltered unvalidated raw value
     * 
     * @var mixed
     */
    private $rawValue;
    
    /**
     * Internally used Sanitizer-object that does not sanitize at all but uses
     * FILTER_UNSAFE_RAW. Useful to benefit from Sanitors methods, without
     * having to actually sanitize anything yet.
     * 
     * @var \Sanitor\Sanitizer
     */
    private $unsafeRawSanitizer;
    
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
        
        return $this;
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
        
        if(!$this->unsafeRawSanitizer instanceof \Sanitor\Sanitizer) {
            $this->unsafeRawSanitizer = new \Sanitor\Sanitizer(FILTER_UNSAFE_RAW);
        }
                
        switch($type) {
            case INPUT_COOKIE:
                return $this->setRawValue($this->unsafeRawSanitizer->filterCookie($variableName));
            case INPUT_ENV:
                return $this->setRawValue($this->unsafeRawSanitizer->filterEnv($variableName));
            case INPUT_GET:
                return $this->setRawValue($this->unsafeRawSanitizer->filterGet($variableName));
            case INPUT_POST:
                return $this->setRawValue($this->unsafeRawSanitizer->filterPost($variableName));
            case INPUT_SERVER:
                return $this->setRawValue($this->unsafeRawSanitizer->filterServer($variableName));
            case INPUT_REQUEST:
                return $this->setRawValue($this->unsafeRawSanitizer->filterRequest($variableName));
            case INPUT_SESSION:
                return $this->setRawValue($this->unsafeRawSanitizer->filterSession($variableName));
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
    
    /**
     * Adds a SanitorMatchValidator
     * 
     * @return ValidatableInterface
     */
    public function addSanitorMatchValidator() {
        $this->addValidator(new Validator\SanitorMatch($this));
        
        return $this;
    }
}
