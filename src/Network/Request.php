<?php

namespace Meride\Network;

use Meride\Network\Token;
use Meride\Network\Response;
use Meride\Network\Error;

class Request
{
    private static $instance = null;
    private $authCode = null;
    private $authURL = null;
    private $token = null;

    public function __construct()
    {}

    public function setAuthCode($authCode)
    {
        $this->authCode = $authCode;
    }

    public function setAuthURL($authURL)
    {
        $this->authURL = $authURL;
    }

    public function getLastError()
    {
        return $this->$error;
    }

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

    public static function instance()
    {
        if (empty(self::$instance)) {
            self::$instance = new Request();
        }
        return self::$instance;
    }

    public function forceDeleteVideo($id_video)
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
    }


    public static function getErrorString($res)
    {
        $messaggio = "";

        if (!isset($res->errors))
            return false;

        foreach ($res->errors as $key => $message) {
            $messaggio .= $key . " - " . $message . PHP_EOL;
        }

        return $messaggio;
    }

    public function get($entityName, $id = null) {
        $resource = $entityName.".json";
        if (!empty($id))
        {
            $resource = $entityName."/".$id.".json";
        }
        return $this->request($resource, 'GET');
    }

    public function put($entityName, $id, $values) {
        return $this->request($entityName."/".$id.".json", 'PUT', $values);
    }

    public function delete($entityName, $id) {
        return $this->request($entityName."/".$id.".json", 'DELETE');
    }

    public function post($entityName, $values) {
        return $this->request($entityName.".json", 'POST', $values);
    }

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

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $resourceURL);

        $content = curl_exec($ch);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($verb == 'DELETE') {
            $res = json_decode($content);
        } else {
            $res = $content;
        }

        if (isset($res->errors) || !$res || (int)$httpcode >= 400) {
            return new Response(false, new Error($httpcode, self::getErrorString($res)));
        }

        return ($res) ? new Response($res, false) :  new Response(array(), false);
    }

    
}
