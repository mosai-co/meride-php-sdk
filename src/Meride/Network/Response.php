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
    public $errors = array();
    /**
     * Response constructor
     * @param string $content The response content
     * @param Meride\Network\Error $error The error return by the REST API
     */
    public function __construct($content, $error = false)
    {
        $this->content = $content;
        $this->jsonContent = json_decode($content);
        $this->error = $error;
    }
}