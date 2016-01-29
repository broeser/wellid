<?php
namespace Wellid\Exception;
/**
 * File not Found Exception
 */
class FileNotFound extends NotFound {
    /**
     * Constructor
     * 
     * @param string $file Filename
     * @param string $fileType File type
     * @param int $code Error code
     * @param \Exception $previous Previous Exception
     */
    public function __construct($file, $fileType, $code = 0, \Exception $previous = null) {
        parent::__construct('File '.$file.' ['.$fileType.']', basename($file), $code, $previous);
    }
}