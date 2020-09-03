<?php

namespace Meride\Storage\Tus;

use TusPhp\Tus\Client as TusClient;

class Client {
    /**
     * The protocol of the service URL (typically HTTPS)
     *
     * @var string
     */
    protected $protocol = 'https';
    /**
     * The upload path for the service.
     * As for the Meride service storage is an immutable value but in this way is manageable
     *
     * @var string
     */
    protected $uploadPath = 'uploads/files';
    /**
     * The base URL for the TUS service
     *
     * @var string
     */
    protected $serviceBasePath = 'https://storageapi.meride.tv';
    /**
     * The upload token after the call to the auth service resource
     *
     * @var string
     */
    protected $uploadToken = '';
    /**
     * The options to be passed to the Tus service calls
     *
     * @var array
     */
    protected $options = [
        'headers' => [
            'AuthType' => 'user',
            'Authorization' => 'Bearer xxx'
        ]
    ];
    /**
     * The Tus client from ankitpokhrel/tus-php package
     *
     * @var TusPhp\Tus\Client
     */
    private $tusClient = null;
    /**
     * Client constructor
     *
     * @param String $uploadToken The upload token after the call to the auth service resource
     * @param String $serviceBasePath The base URL for the TUS service
     * @param String $uploadPath The upload path for the service
     * @param Array $options The options to be passed to the Tus service calls
     */
    public function __construct(
        String $uploadToken = '',
        String $serviceBasePath = '',
        String $uploadPath = '',
        Array $options = []
    )
    {
        $this->setServiceBasePath($serviceBasePath);
        $this->setUploadPath($uploadPath);
        $this->setUploadToken($uploadToken);
        $this->setOptions($options);
        $this->tusClient = new TusClient($this->getServiceBasePath(), $this->getOptions());
        $this->tusClient->setApiPath($this->getUploadPath());
    }
    /**
     * Uploads a file to the storage service
     *
     * @param String $inputFilePath Local file path
     * @param String $outputFileName The name of the output file
     * @return mixed A URL of the video representation or false if some error occurs
     */
    public function upload(String $inputFilePath, String $outputFileName)
    {
        //$key = hash_file('md5', $inputFilePath);
        $key = md5($inputFilePath);
        // setting the key is a mandatory step
        $this->tusClient->setKey($key);
        // upload the file
        try {
            $this->tusClient->file($inputFilePath, $outputFileName)->upload();
        } catch (\Exception $e)
        {
            throw $e;
        }
        return $this->tusClient->getUrl();
    }
    /**
     * Extracts the video URL from the TUS upload URL
     *
     * @param String $uploadURL
     * @return string
     */
    public function extractURL(String $uploadURL)
    {
        return $this->protocol.':'.str_replace($this->uploadPath, "file", $uploadURL);
    }
    /**
     * Get the protocol of the service URL (typically HTTPS)
     *
     * @return  string
     */ 
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Set the protocol of the service URL (typically HTTPS)
     *
     * @param  string  $protocol  The protocol of the service URL (typically HTTPS)
     *
     * @return  self
     */ 
    public function setProtocol(string $protocol)
    {
        if (!empty($protocol))
        {
            $this->protocol = $protocol;
        }
        return $this;
    }

    /**
     * Get as for the Meride service storage is an immutable value but in this way is manageable
     *
     * @return  string
     */ 
    public function getUploadPath()
    {
        return $this->uploadPath;
    }

    /**
     * Set as for the Meride service storage is an immutable value but in this way is manageable
     *
     * @param  string  $uploadPath  As for the Meride service storage is an immutable value but in this way is manageable
     *
     * @return  self
     */ 
    public function setUploadPath(string $uploadPath)
    {
        if (!empty($uploadPath))
        {
            $this->uploadPath = $uploadPath;
            if (!empty($this->tusClient))
            {
                $this->tusClient->setApiPath($this->getUploadPath());
            }
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
     * Get the upload token after the call to the auth service resource
     *
     * @return  string
     */ 
    public function getUploadToken()
    {
        return $this->uploadToken;
    }

    /**
     * Set the upload token after the call to the auth service resource
     *
     * @param  string  $uploadToken  The upload token after the call to the auth service resource
     *
     * @return  self
     */ 
    public function setUploadToken(string $uploadToken)
    {
        if (!empty($uploadToken))
        {
            $this->uploadToken = $uploadToken;
            if(isset($this->options['headers']) && isset($this->options['headers']['Authorization']))
            {
                $this->options['headers']['Authorization'] = 'Bearer '.$uploadToken;
            }
        }
        return $this;
    }

    /**
     * Get the options to be passed to the Tus service calls
     *
     * @return  array
     */ 
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set the options to be passed to the Tus service calls
     *
     * @param  array  $options  The options to be passed to the Tus service calls
     *
     * @return  self
     */ 
    public function setOptions(array $options)
    {
        if (!empty($options))
        {
            $this->options = $options;
        }
        return $this;
    }
}