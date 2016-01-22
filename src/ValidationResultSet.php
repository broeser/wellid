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
 * A collection of ValidationResults can be stored in a ValidationResultSet
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class ValidationResultSet implements \Iterator, \Countable {
    /**
     *  Array of ValidationResults in this Set
     * 
     * @var ValidationResult[]
     */
    private $entries = array();    
    
    /**
     * Internal Iterator index
     * 
     * @var integer
     */
    private $position = 0;
    
    /**
     * Returns whether there are error messages in this ValidationResultSet
     * 
     * @return boolean
     */
    public function hasErrors() {
        foreach($this->entries as $entry) {
            if($entry->isError()) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Returns the first error result, or null if there is no error
     * 
     * @return ValidationResult|null
     */
    public function firstError() {
        foreach($this->entries as $entry) {
            if($entry->isError()) {
                return $entry;
            }
        }
        
        return null;
    }
    
    /**
     * Returns whether there are only passing ValidationResults in this
     * ValidationResultSet
     * 
     * @return boolean
     */
    public function hasPassed() {
        return !$this->hasErrors();
    }
    
    /**
     * Adds a ValidationResult to the ValidationResultSet
     * 
     * @param ValidationResult $result
     */
    public function add(ValidationResult $result) {
        $this->entries[] = $result;
    }
    
    /**
     * Adds a ValidationResultSet to this ValidationResultSet
     * 
     * @param \Wellid\ValidationResultSet $resultSet
     */
    public function addSet(ValidationResultSet $resultSet) {
        foreach($resultSet as $result) {
            $this->add($result);
        }
    }
    
    /*
     * Iterator-methods
     */
    
    /**
     * Returns the current (Iterator) ValidationResult
     * 
     * @return ValidationResult
     */
    public function current() {
        return $this->entries[$this->position];
    }

    /**
     * Returns the current Iterator position
     * 
     * @return integer
     */
    public function key() {
        return $this->position;
    }

    /**
     * Increases the Iterator position
     */
    public function next() {
        ++$this->position;
    }

    /**
     * Rewinds the Iterator position
     */
    public function rewind() {
        $this->position = 0;
    }

    /**
     * Returns whether a certain entry is set (Iterator-interface)
     * !!! Despite the method name has nothing to do with validation !!!
     * 
     * @return boolean
     */
    public function valid() {
        return isset($this->entries[$this->position]);
    }
    
    /* Countable-methods */

    /**
     * Returns the number of ValidationResults in this set
     * 
     * @return int
     */
    public function count() {
        return count($this->entries);
    }
}
