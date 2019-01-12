<?php

namespace Meride\Network;

class Token {
    public $accessToken = '';
    public $refreshToken = '';
    private $authCode = '';
    private $authURL = '';
    
    public function __construct($authCode, $authURL)
    {
        $this->authCode = $authCode;
        $this->authURL = $authURL;
    }

    private function validToken()
    {
        // se c'è bisogno di generarne uno nuovo dopo la scadenza
        return true;
    }

    private function refreshToken()
    {
        // richiede un nuovo auth token utilizzando il refresh_token
    }

    private function generateState()
    {
        return "".rand(0, 999999);
    }

    public function generate()
    {
        if (!empty($this->refreshToken) and !empty($this->accessToken)) {
            if (!$this->validToken()) {
                $this->refreshToken;
            }
        }
        $state = $this->generateState();
        $headers = array(
            'Accept: application/json',
            'auth-code: ' . $this->authCode,
            'state: ' . $state,
        );

        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($c, CURLOPT_URL, $this->authURL . '/restauth/verify');

        $content = curl_exec($c);
        curl_close($c);
        $obj = json_decode($content);
        
        if (isset($obj->errors)) {
            throw new \Exception(implode(',', $obj->errors));
        }
        if (!isset($obj->state)) {
            $this->error = 'state not defined';
            throw new \Exception('State not defined');
        } else {
            if ($state != $obj->state) {
                $this->error = 'state not equal';
                throw new \Exception('State not equal');
            }
        }

        if (!isset($obj->access_token)) {
            $this->error = 'no access token';
            throw new \Exception('No access-token');
        } else {
            $this->accessToken = $obj->access_token;
        }

        if (isset($obj->refresh_token)) {
            $this->refreshToken = $obj->refresh_token;
        }
    }
}