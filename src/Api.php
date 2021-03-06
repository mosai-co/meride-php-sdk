<?php
/**
 * Api entrypoint
 * 
 * @category Meride
 * @package  Meride
 * @author   Toppi Giovanni Manuel @MerideDevTeam <giovanni.toppi@mosai.co>
 * @license  prorietary https://www.meride.tv
 * @link     https://www.meride.tv/docs
 * @todo     Write better documentation about the parameters to pass to the various methods
 */
namespace Meride;

use Meride\Network\Request;
use Meride\Network\Response;
use Meride\MerideCollection;
use Meride\MerideEntity;
/**
 * Interface/SDK of Meride's REST APIs
 * REST API documentation at: www.meride.tv/docs/api/
 * @package Meride\Api
 */
class Api
{
    /**
     * Reference to Meride\Network\Request
     * @var Meride\Network\Request
     * @private
     */
    private $request = null;
    /**
     * Api constructor
     * @param String $authCode The auth code of Meride's REST API
     * @param String $authURL The base URL of Meride's REST API
     * @param String $version The version of the API
     */
    public function __construct($authCode, $authURL, $version = '')
    {
        $this->request = new Request();
        $this->request->init($authCode, $authURL, $version);
    }
    /**
     * Produces a MerideEntity from a Meride\Network\Response
     *
     * @param Meride\Network\Response $originalResponse
     * @return mixed $response if some error exists Meride\Network\Response otherwise Meride\MerideEntity. If there is an empty response it will return null
     */
    private function entity(Network\Response $originalResponse)
    {
        return new MerideEntity($originalResponse);
    }
    /**
     * Produces a MerideCollection from a Meride\Network\Response
     *
     * @param Meride\Network\Response $originalResponse
     * @return MerideCollection If there is an empty response it will return null
     */
    private function collection(Network\Response $originalResponse)
    {
        return new MerideCollection($originalResponse);
    }
    /**
     * Creates a new object of the given entity type
     * @param String $entityName The name of the entity in use (eg. 'video', 'embed', ...)
     * @param Array $values An associative array of the data to assign to the new object
     * @return Meride\Network\Response The response for the created object/error
     */
    public function create($entityName, $values)
    {
        $response = $this->request->post($entityName, $values);
        return $this->entity($response);
    }
    /**
     * @alias Api::create
     */
    public function post($entityName, $values)
    {
        return $this->create($entityName, $values);
    }
    /**
     * Reads an object of the given entity type with the given id
     * @param String $entityName The name of the entity in use (eg. 'video', 'embed', ...)
     * @param String|\Number $id The id of the desired object
     * @param Array $params An associative array to transorm to GET parameters
     * @return Network\Response The response for the object/error
     */
    public function read($entityName, $id = null, array $params = [])
    {
        $response = $this->request->get($entityName, $id, $params);
        return $this->entity($response);
    }
    /**
     * @alias Api::read
     */
    public function get($entityName, $id = null, array $params = [])
    {
        return $this->read($entityName, $id, $params);
    }
    /**
     * Reads a list of objects of the given entity type
     * @param String $entityName The name of the entity in use (eg. 'video', 'embed', ...)
     * @param Array $params An associative array to transorm to GET parameters
     * @return MerideCollection The response for the object/error
     */
    public function all($entityName, array $params = [])
    {
        $response = $this->request->all($entityName, $params);
        return $this->collection($response);
    }
    /**
     * Updates the object of the given entity type with the given id
     * @param String $entityName The name of the entity in use (eg. 'video', 'embed', ...)
     * @param String|\Number $id The id of the desired object to update
     * @param Array $values An associative array of the data to assign to the new object
     * @return Network\Response The response for the updated object/error
     */
    public function update($entityName, $id, $values)
    {
        $response = $this->request->put($entityName, $id, $values);
        return $this->entity($response);
    }
    /**
     * @alias Api::update
     */
    public function put($entityName, $id,  $values)
    {
        return $this->update($entityName, $id, $values);
    }
    /**
     * Deletes an object of the given entity type with the given id
     * @param String $entityName The name of the entity in use (eg. 'video', 'embed', ...)
     * @param String|\Number $id The id of the desired object
     * @return Network\Response The response for the deleted object
     */
    public function delete($entityName, $id)
    {
        return $this->request->delete($entityName, $id);
    }
    /**
     * Search method. Whan called search parameters should be passed instead of the read ones.
     * The method will not check if the parameters are part of the search feature.
     * @alias Api::all
     */
    public function search($entityName, array $params = [])
    {
        return $this->all($entityName, $params);
    }
    /**
     * Returns the current authentication token
     *
     * @return mixed
     */
    public function getToken()
    {
        return empty($this->request) ? null : $this->request->getToken();
    }
    
    /*protected function getServiceClass($name)
    {
        return \array_key_exists($name, self::$classMap) ? self::$classMap[$name] : null;
    }*/
}
