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
 * Description of MaxDate
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class MaxDate implements ValidatorInterface {   
    use ValidatorTrait;
    
    /**
     * Date format, default is YYYY-MM-DD (Y-m-d)
     * 
     * @link http://php.net/manual/en/function.date.php
     * @var string
     */
    protected $format = 'Y-m-d';
    
    /**
     * Maximum
     * 
     * @var \DateTimeInterface
     */
    protected $max;
    
    /**
     * Constructor
     * 
     * @param string|\DateTimeInterface $max
     */
    public function __construct($max, $format = null) {
        if(is_string($format)) {
            $this->format = $format;
        } elseif(!is_null($format)) {
            throw new \Wellid\Exception\DataType('format', 'string', $format);
        }
        
        if($max instanceof \DateTimeInterface) {
            $this->max = $max;
            return;
        }
        
        if((new Date($this->format))->validate($max)->isError()) {
            throw new \Wellid\Exception\DataFormat('max', 'date string in the format Y-m-d or instance of DateTimeInterface', $max);
        }
        
        $this->max = \DateTimeImmutable::createFromFormat($this->format, $max);
    }
    
    /**
     * Validates the given $value
     * Checks if it is after the given maximum date
     * 
     * @param string $value
     * @return ValidationResult
     */
    public function validate($value) {
        $isDate = (new Date($this->format))->validate($value);
    
        if($isDate->isError()) {
            return $isDate;
        }
        
        if(\DateTime::createFromFormat($this->format, $value)>$this->max) {
            return new ValidationResult(false, 'Date is too late');
        }

        return new ValidationResult(true);
    }
}
