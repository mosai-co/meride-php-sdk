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
        } else if (isset($apiResponse->jsonContent) && !isset($apiResponse->jsonContent->data)) {
            $data = $apiResponse->jsonContent;
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
     * Counts the number of items stored in memory.
     * To get the total numer of items call the total() method.
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
     * Gets the API response received from the service
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
        return $this->apiResponse->hasErrors();
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
    /**
     * The starting record index based on the pagination.
     * I.e. if search_for_page=3 and search_page=2 it will take 4 because the second page of a 3 elements pagination starts from the fourth element
     * @return integer
     */
    public function from()
    {
        return $this->apiResponse->jsonContent->from ?? null;
    }
    /**
     * The current page of the query execution. The default value is 1. If search_page is passed it will get that value.
     * @return integer
     */
    public function currentPage()
    {
        return $this->apiResponse->jsonContent->current_page ?? null;
    }
    /**
     * The URL to the CMS, together with the GET parameters, to access the first page with the pagination defined for search_for_page, which by default is 100.
     * @return string
     */
    public function firstPageUrl()
    {
        return $this->apiResponse->jsonContent->first_page_url ?? null;
    }
    /**
     * The last page number based on the pagination
     * @return integer
     */
    public function lastPage()
    {
        return $this->apiResponse->jsonContent->last_page ?? null;
    }
    /**
     * The URL to the CMS, together with the GET parameters, to access the last page with the pagination defined for search_for_page, which by default is 100.
     * @return string
     */
    public function lastPageUrl()
    {
        return $this->apiResponse->jsonContent->last_page_url ?? null;
    }
    /**
     * The URL to the CMS, together with the GET parameters, to access the next page compared to the current page, or null if not available.
     * @return string
     */
    public function nextPageUrl()
    {
        return $this->apiResponse->jsonContent->next_page_url ?? null;
    }
    /**
     * The number of elements per page. It the parametr search_for_page is set it will take that value. Default is 100.
     * @return integer
     */
    public function perPage()
    {
        return $this->apiResponse->jsonContent->per_page ?? null;
    }
    /**
     * The URL to the CMS, together with the GET parameters, to access the previous page compared to the current page, or null if not available.
     * @return string
     */
    public function prevPageUrl()
    {
        return $this->apiResponse->jsonContent->prev_page_url ?? null;
    }
    /**
     * The ending record index based on the pagination.
     * I.e. if search_for_page=3 and search_page=2 it will take 6 because the last page of a 3 elements pagination ends with the sixth element
     * @return integer
     */
    public function to()
    {
        return $this->apiResponse->jsonContent->to ?? null;
    }
    /**
     * The total number of items for that query without considering the pagination.
     * @return integer
     */
    public function total()
    {
        return $this->apiResponse->jsonContent->total ?? null;
    }
}