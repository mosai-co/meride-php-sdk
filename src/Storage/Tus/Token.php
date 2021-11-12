<?php

namespace Meride\Storage\Tus;

class Token
{
    /**
     * The authorization path for the Tus service
     *
     * @var string
     */
    protected $authPath = 'merideauth/token';
    /**
     * The base URL for the TUS service
     *
     * @var string
     */
    protected $serviceBasePath = 'https://storageapi.meride.tv';
    /**
     * The Meride client name
     *
     * @var string
     */
    protected $username = '';
    /**
     * Meride client's auth code
     *
     * @var string
     */
    protected $authCode = '';
    /**
     * TUS Token contructor
     *
     * @param String $username
     * @param String $authCode
     * @param String $serviceBasePath
     * @param String $authPath
     */
    public function __construct(
        String $username = '',
        String $authCode = '',
        String $serviceBasePath = '',
        String $authPath = ''
    )
    {
        $this->setUsername($username);
        $this->setAuthCode($authCode);
        $this->setServiceBasePath($serviceBasePath);
        $this->setAuthPath($authPath);
    }
    /**
     * Generates a token calling the Tus service
     *
     * @return void
     */
    public function generate()
    {
        $curlHandle = curl_init();
        $params = [
            'username' => $this->getUsername(),
            'authcode' => $this->getAuthCode(),
            'state' => $this->generateState()
        ];
        curl_setopt($curlHandle, CURLOPT_URL, $this->getServiceBasePath().'/'.$this->getAuthPath());
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, \http_build_query($params));
        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);

        $content = curl_exec($curlHandle);
        if($content === false) {
            $error = curl_error($curlHandle);
            curl_close($curlHandle);
            throw new \Exception($error);
        }
        $httpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
        curl_close($curlHandle);
        $result = json_decode($content);
        if ($httpCode != 200) {
            throw new \Exception("Unable to authenticate");
        }
        if (!isset($result->state)) {
            throw new \Exception('State not defined');
        } else if ($params['state'] != $result->state) {
            throw new \Exception('State not equal');
        }
        if (!isset($result->token)) {
            throw new \Exception('Token not found in the response');
        }
        return $result->token;
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
     * Get the authorization path for the Tus service
     *
     * @return  string
     */ 
    public function getAuthPath()
    {
        return $this->authPath;
    }

    /**
     * Set the authorization path for the Tus service
     *
     * @param  string  $authPath  The authorization path for the Tus service
     *
     * @return  self
     */ 
    public function setAuthPath(string $authPath)
    {
        if (!empty($authPath))
        {
            $this->authPath = $authPath;
        }
        return $this;
    }

    /**
     * Get the base URL for the TUS service
     *
     * @return  string
     */ 
    public function getServiceBasePath()
    {
        return $this->serviceBasePath;
    }

    /**
     * Set the base URL for the TUS service
     *
     * @param  string  $serviceBasePath  The base URL for the TUS service
     *
     * @return  self
     */ 
    private function setServiceBasePath(string $serviceBasePath)
    {
        if (!empty($serviceBasePath))
        {
            $this->serviceBasePath = $serviceBasePath;
        }
        return $this;
    }

    /**
     * Get the Meride client name
     *
     * @return  string
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the Meride client name
     *
     * @param  string  $username  The Meride client name
     *
     * @return  self
     */ 
    private function setUsername(string $username)
    {
        if (!empty($username))
        {
            $this->username = $username;
        }
        return $this;
    }

    /**
     * Get meride client's auth code
     *
     * @return  string
     */ 
    public function getAuthCode()
    {
        return $this->authCode;
    }

    /**
     * Set meride client's auth code
     *
     * @param  string  $authCode  Meride client's auth code
     *
     * @return  self
     */ 
    public function setAuthCode(string $authCode)
    {
        if (!empty($authCode))
        {
            $this->authCode = $authCode;
        }
        return $this;
    }
}