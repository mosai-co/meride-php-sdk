<?php

namespace Meride;

use Meride\MerideObject;
use Meride\Network\Response;

class MerideEntity extends MerideObject
{
    /**
     * The API response received from the service
     *
     * @var Meride\Network\Response
     */
    protected $apiResponse;
    /**
     * MerideEntity constructor
     *
     * @param Response $apiResponse
     */
    public function __construct(Response $apiResponse)
    {
        $this->apiResponse = $apiResponse;
        if (isset($this->apiResponse->jsonContent)) {
            parent::__construct((array)$this->apiResponse->jsonContent);
        }
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
}