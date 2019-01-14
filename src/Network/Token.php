<?php

namespace Meride\Network;
/**
 * Token class will manage REST API token system
 */
class Token {
    /**
     * The access token is the string that will be returned after the first call to the API.
     * It's the real key that API will use in order to authenticate the call
     * @var string
     */
    public $accessToken = '';
    /**
     * The refresh token is the string that will be used when the access token expires in order to generate a new one.
     * @var string
     */
    public $refreshToken = '';
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
     * The version of the API
     * @var string
     */
    private $version = '';
    /**
     * Token constructor
     *
     * @param string $authCode Is the primary authorization string used for the first authentication to the API
     * @param string $authURL The base URL of the API
     * @param string $version The version of the API
     */
    public function __construct($authCode, $authURL, $version = '')
    {
        $this->authCode = $authCode;
        $this->authURL = $authURL;
        if (!empty($this->authURL) and substr($this->authURL, -1) != '/')
        {
            $this->authURL .= '/';
        }
        $this->version = $version;
    }
    /**
     * Check if the access token is valid
     * @todo To be implemented. Now it always returns true
     * @return boolean
     */
    private function validToken()
    {
        return true;
    }
    /**
     * It will generate a new access token using the refresh token
     * @todo To be implemented
     * @return void
     */
    private function refreshToken()
    {
        // richiede un nuovo auth token utilizzando il refresh_token
    }
    /**
     * It generates a state useful as a security system by the remote API
     * @return string
     */
    private function generateState()
    {
        return "".rand(0, 999999);
    }
    /**
     * Function that generates the access token and the refresh token
     * @return string
     */
    public function generate()
    {
        if (!empty($this->refreshToken) and !empty($this->accessToken)) {
            if (!$this->validToken()) {
                $this->refreshToken();
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
        if (empty($this->version))
        {
            curl_setopt($c, CURLOPT_URL, $this->authURL.'restauth/verify');
        }
        else
        {
            curl_setopt($c, CURLOPT_URL, $this->authURL. 'restauth/'.$this->version.'/verify');
        }

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