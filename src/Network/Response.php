<?php

namespace Meride\Network;

use Meride\Network\Error;
/**
 * Network Response
 * @class Response
 * @namespace Meride\Network
 */
class Response {
    public $content = null;
    public $jsonContent = null;
    public $httpCode = null;
    public $error = null;
    /**
     * Response constructor
     * @param string $content The response content
     * @param Meride\Network\Error $error The error returned by the REST API
     */
    public function __construct($content, $error = false, $httpCode = null)
    {
        $this->content = $content;
        $this->jsonContent = json_decode($content);
        $this->error = $error;
        $this->httpCode = $httpCode;
    }
    /**
     * Returns the number of elements of the response
     * @return int
     */
    public function count()
    {
        if (!empty($this->jsonContent) and is_array($this->jsonContent))
        {
            return count($this->jsonContent);
        }
        return 0;
    }
    /**
     * Returns true if the original API response contains errors, false otherwise
     *
     * @return  boolean
     */ 
    public function hasErrors()
    {
        return !empty($this->error);
    }
    /**
     * Returns if the API response is empty (without considering the error)
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->content) AND empty($this->jsonContent);
    }
}