<?php

namespace Meride;

use Meride\MerideObject;
use Meride\Network\Response;

class MerideCollection extends MerideObject implements \Countable, \IteratorAggregate
{
    /**
     * The API response received from the service
     *
     * @var Meride\Network\Response
     */
    protected $apiResponse;
    /**
     * MerideCollection constructor
     *
     * @param Response $apiResponse
     */
    public function __construct(Response $apiResponse)
    {
        $this->apiResponse = $apiResponse;
        $data = null;
        $dataCopy = [];
        if (isset($apiResponse->jsonContent) && isset($apiResponse->jsonContent->data)) {
            $data = $apiResponse->jsonContent->data;
        }
        if (is_array($data)) {
            $dataArrayObject = new \ArrayObject($data);
            $dataCopy = $dataArrayObject->getArrayCopy();
            foreach ($dataCopy as $key => $value) {
                $dataCopy[$key] = new MerideObject((array)$value);
            }
        }
        $this->data = $dataCopy;
    }
    /**
     * Count the number of the items
     *
     * @return integer
     */
    public function count()
    {
        return \count($this->data);
    }
    /**
     * @return \ArrayIterator an iterator for the data
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }
    /**
     * Get the API response received from the service
     *
     * @return  Meride\Network\Response
     */ 
    public function getApiResponse()
    {
        return $this->apiResponse;
    }
    /**
     * Returns true if the original API response contains errors, false otherwise
     *
     * @return  boolean
     */ 
    public function hasErrors()
    {
        return !empty($this->apiResponse->error);
    }
    /**
     * @see Meride\Network\Response::isEmpty
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return $this->apiResponse->isEmpty();
    }
}