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
namespace Wellid\Cache;

/**
 * This is not part of the public API.
 * 
 * @internal Trait used for everything that supports caching a ValidationResultSet.
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
trait CacheValidationResultSetTrait {   
    /**
     * ValidationResultSet of the last validation of this
     * 
     * @var \Wellid\ValidationResultSet
     */
    protected $lastValidationResult = null;
    
    /**
     * Whether the cache is on (default) or off fro this object
     * 
     * @var boolean
     */
    protected $cacheValidationResult = true;

    /**
     * Removes the last ValidationResultSet from cache in order to re-validate 
     * this (usually not necessary)
     */
    public function clearValidationResult() {
        $this->lastValidationResult = null;
        
        return $this;
    }
    
    /**
     * Disables caching of ValidationResultSets (only when validating this
     * object).
     * 
     * @return CacheableValidatableInterface
     */
    public function disableValidationCache() {
        $this->clearValidationResult();
        $this->cacheValidationResult = false;
        
        return $this;
    }
    
    /**
     * Returns whether caching of ValidationResultSets is enabled for this 
     * object
     * 
     * @return boolean
     */
    public function isValidationCacheEnabled() {
        return $this->cacheValidationResult;
    }
}