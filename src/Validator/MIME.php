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
use Wellid\Exception\DataFormat;
use Wellid\Exception\DataType;
/**
 * Checks the MIME type of an uploaded file, expects the $_FILES['filename']
 * array (or a similarily-formed array) as input
 *
 * @author Benedict Roeser <b-roeser@gmx.net>
 */
class MIME implements ValidatorInterface {
    use ValidatorTrait;
    
    /**
     * Mediatype of MIME type
     * 
     * @var string
     */
    protected $mimeMediatype;
    
    /**
     * Subtype of MIME type
     * 
     * @var type 
     */
    protected $mimeSubtype;

    const ERR_MIME = 2;
    const ERR_SUBTYPE = 4;
    
    /**
     * Constructor
     *
     * @param string $mime MIME type (with wildcard support e. g. text/*)
     */
    public function __construct($mime) {
        if(!is_string($mime)) {
            throw new DataType('mime', 'string', $mime);
        }
        
        $mimeParts = explode('/', $mime);
        
        if(count($mimeParts)!==2) {
            throw new DataFormat('mime', 'mediatype/subtype', $mime);
        }
        
        list($this->mimeMediatype, $this->mimeSubtype) = $mimeParts;
    }
    
    /**
     * Validates the given $value
     * Checks if it points to a file with the specified MIME type
     * 
     * @param string $filename
     * @return ValidationResult
     */
    public function validate($filename) {
        if(!is_string($filename)) {
            throw new DataType('value', 'string', $filename);
        }
            
        $fileInfo = new \finfo(FILEINFO_MIME_TYPE);            
        $mimeType = $fileInfo->file($filename);
        
        if($mimeType===false) {
            throw new \Wellid\Exception\FileNotFound($filename, 'file for MIME validator');
        }
        
        $mimeParts = explode('/', $mimeType);
            
        if(count($mimeParts)!==2) {
            throw new DataFormat('mime', 'mediatype/subtype', $mimeType);
        }
            
        /*
         * Media type of the given file is not identical with the expected
         * media type, e. g. image/* was expected, but audio/... was given
         */
        if($this->mimeMediatype!=$mimeParts[0]) {
            return new ValidationResult(false, 'Invalid MIME type', self::ERR_MIME);
        }

        /*
         * Media subtype of the given file is not identical with the expected
         * media subtype - and the expected subtype is not the wildcard (*)
         * e. g. image/jpeg was expected, but image/png was given
         */
        if($this->mimeSubtype!=='*' && $this->mimeSubtype!=$mimeParts[1]) {
            return new ValidationResult(false, 'Invalid MIME subtype', self::ERR_SUBTYPE);
        }     
        
        return new ValidationResult(true);
    }
}
