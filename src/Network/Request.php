<?php

namespace Meride\Network;

use Meride\Network\Token;
use Meride\Network\Response;
use Meride\Network\Error;
/**
 * Front manager for the requests to the API
 * @class Request
 * @namespace Meride\Network
 */
class Request
{
    /**
     * instance for singleton
     * @var Meride\Network\Request
     */
    private static $instance = null;
    /**
     * Is the primary authorization string used for the first authentication to the API
     * @var string
     */
    private $authCode = '';
    /**
     * The base URL of the API
     * @var string
     */
    private $authURL = '';
    /**
     * Reference to the Token object
     * @var Meride\Network\Token
     */
    private $token = null;

    public function __construct()
    {}
    /**
     * Sets the authorization code
     * @param string $authCode the authorization code
     * @return void
     */
    public function setAuthCode($authCode)
    {
        $this->authCode = $authCode;
    }
    /**
     * Sets the authorization URL
     * @param string $authCode the authorization URL
     * @return void
     */
    public function setAuthURL($authURL)
    {
        $this->authURL = $authURL;
    }
    /**
     * Function that has to be called after Request initialization
     * @param string $authCode the authorization code
     * @param string $authCode the authorization URL
     * @return void
     */
    public function init($authCode = null, $authURL = null)
    {
        if (!empty($authCode))
        {
            $this->setAuthCode($authCode);
        }
        if (!empty($authURL))
        {
            $this->setAuthURL($authURL);
        }
        $this->token = new Token($this->authCode, $this->authURL);
        $this->token->generate();
    }
    /**
     * Return the instance/singleton of the current object
     * @return Meride\Network\Request
     */
    public static function instance()
    {
        if (empty(self::$instance)) {
            self::$instance = new Request();
        }
        return self::$instance;
    }

    /*public function forceDeleteVideo($id_video)
    {
        $embed_list = $this->request("allEmbed", array('search_video_id' => $id_video));

        if (!$embed_list)
            return true;


        foreach ($embed_list as $key => $embed) {

                $check = $this->request("removeEmbed", array("id" => $embed->id));

                if (!$check) {
                    return false;
                }

        }

        $check = $this->request("removeVideo", array("id" => $id_video));

        if (!$check) {
            return false;
        }


        return true;
    }*/
    /**
     * Parse the error return by the REST service into a string
     * @param object $res the response of the REST API service
     * @return string
     */
    private static function getErrorString($res)
    {
        $messaggio = "";
        if (!isset($res->errors))
        {
            return false;
        }
        foreach ($res->errors as $key => $message)
        {
            $messaggio .= $key . " - " . $message . PHP_EOL;
        }
        return $messaggio;
    }
    /**
     * Reads an object of the given entity type with the given id
     * @param String $entityName The name of the entity in use (eg. 'video', 'embed', ...)
     * @param String|\Number $id The id of the desired object
     * @return Network\Response The response for the object/error
     */
    public function get($entityName, $id = null) {
        $resource = $entityName.".json";
        if (!empty($id))
        {
            $resource = $entityName."/".$id.".json";
        }
        return $this->request($resource, 'GET');
    }
    /**
     * Updates the object of the given entity type with the given id
     * @param String $entityName The name of the entity in use (eg. 'video', 'embed', ...)
     * @param String|\Number $id The id of the desired object to update
     * @param Array $values An associative array of the data to assign to the new object
     * @return Network\Response The response for the updated object/error
     */
    public function put($entityName, $id, $values) {
        return $this->request($entityName."/".$id.".json", 'PUT', $values);
    }
    /**
     * Deletes an object of the given entity type with the given id
     * @param String $entityName The name of the entity in use (eg. 'video', 'embed', ...)
     * @param String|\Number $id The id of the desired object
     * @return Network\Response The response for the deleted object
     */
    public function delete($entityName, $id) {
        return $this->request($entityName."/".$id.".json", 'DELETE');
    }
    /**
     * Creates a new object of the given entity type
     * @param String $entityName The name of the entity in use (eg. 'video', 'embed', ...)
     * @param Array $values An associative array of the data to assign to the new object
     * @return Meride\Network\Response The response for the created object/error
     */
    public function post($entityName, $values) {
        return $this->request($entityName.".json", 'POST', $values);
    }
    /**
     * Performs a request to the REST API service
     * @param string $resource the final path of the service (eg. video.json, video/1.json)
     * @param string $verb the HTTP verb (GET|POST|PUT|DELETE)
     * @param array $params data of the request
     * @return void
     */
    public function request($resource, $verb, $params = array())
    {
        if (empty($this->token)) {
            throw new \Exception("Token uninitialized. Please be sure to call Request->init() method");
        }
        $resourceURL = $this->authURL."/rest/".$resource;
        $ch = curl_init();

        $headers = array(
            'Accept: application/json',
            'access-token: ' . $this->token->accessToken,
        );

        $verb = strtoupper($verb);

        if (in_array($verb, array('POST', 'PUT', 'DELETE')))
        {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        }
        if ($verb == "PUT" or $verb == "DELETE")
        {
            $headers[] = "X-HTTP-Method-Override: ".$verb;
        }
        if (($verb == 'PUT' or $verb == 'POST') and !empty($params)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $resourceURL);
        $content = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($verb == 'DELETE')
        {
            $res = json_decode($content);
        }
        else
        {
            $res = $content;
        }
        if (isset($res->errors) || !$res || (int)$httpcode >= 400)
        {
            return new Response(false, new Error($httpcode, self::getErrorString($res)));
        }
        return ($res) ? new Response($res, false) :  new Response(array(), false);

        /*switch ($action) {
            case 'allVideo':
                $azione = '/rest/video.json';
                break;

            case 'allEmbed':
                $parametri = array();

                if (count($params) > 0) {
                    foreach ($params as $key => $value) {
                        $parametri[] = $key . "=" . $value;
                    }
                }

                $azione = '/rest/embed.json' . (($parametri) ? "?" . implode('&', $parametri) : "");
                break;

            case 'allSocial':
                $azione = '/rest/facebookvideo.json';
                break;

            case 'getVideo':
                $id = $params["id"];
                $azione = "/rest/video/$id.json";
                break;

            case 'getEmbed':
                $id = $params["id"];
                unset($params['id']);
                $azione = "/rest/embed/$id.json";
                break;

            case 'editVideo':
                $headers[] = "X-HTTP-Method-Override: PUT";
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                $id = $params["id"];
                unset($params['id']);
                $azione = "/rest/video/$id.json";
                $need_params = true;
                break;

            case 'editEmbed':
                $headers[] = "X-HTTP-Method-Override: PUT";
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                $id = $params["id"];
                unset($params['id']);
                $azione = "/rest/embed/$id.json";
                $need_params = true;
                break;

            case 'insertVideo':
                $azione = '/rest/video.json';
                $need_params = true;
                break;

            case 'createEmbed':
                $azione = '/rest/embed.json';
                $need_params = true;
                break;

            case 'createSocial':
                $azione = '/rest/facebookvideo.json';
                $need_params = true;
                break;


            case 'removeEmbed':
                $id = $params["id"];
                $headers[] = "X-HTTP-Method-Override: DELETE";
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                $azione = "/rest/embed/$id.json";
                $is_remove = true;
                break;

            case 'removeSocial':
                $id = $params["id"];
                $headers[] = "X-HTTP-Method-Override: DELETE";
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                $azione = "/rest/facebookvideo/$id.json";
                $is_remove = true;
                break;

            case 'removeVideo':
                $id = $params["id"];
                $headers[] = "X-HTTP-Method-Override: DELETE";
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                $azione = "/rest/video/$id.json";
                $is_remove = true;
                break;

            default:
                curl_close($ch);
                return [];
                break;
        }*/
    }

    
}
