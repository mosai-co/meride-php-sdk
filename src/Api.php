<?php

namespace Meride;

use Meride\Network\Request;
use Meride\Network\Response;
/**
 * Interface/SDK of Meride's REST APIs
 * REST API documentation at: www.meride.tv/docs/api/
 * @package Meride\Api
 */
class Api
{
    /**
     * Reference to Meride\Network\Request
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
     * Creates a new object of the given entity type
     * @param String $entityName The name of the entity in use (eg. 'video', 'embed', ...)
     * @param Array $values An associative array of the data to assign to the new object
     * @return Meride\Network\Response The response for the created object/error
     */
    public function create($entityName, $values)
    {
        return $this->request->post($entityName, $values);
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
     * @return Network\Response The response for the object/error
     */
    public function read($entityName, $id = null)
    {
        return $this->request->get($entityName, $id);
    }
    /**
     * @alias Api::read
     */
    public function get($entityName, $id = null)
    {
        return $this->read($entityName, $id);
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
        return $this->request->put($entityName, $id, $values);
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

}
