<?php

namespace Meride\Network;

/**
 * Network Error
 * @class Error
 * @namespace Meride\Network
 */
class Error {
    public $message = '';
    public $errorCode = null;
    /**
     * Error constructor
     * @param string $message The error message
     * @param string|\Number $errorCode The error code
     */
    public function __construct($message, $errorCode)
    {
        $this->message = $message;
        $this->errorCode = $errorCode;
    }
    /**
     * String rendering of the Error
     *
     * @return string
     */
    public function __toString()
    {
        return $this->message." - ".$this->errorCode;
    }
}