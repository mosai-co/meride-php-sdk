<?php
/**
 * Encoder management.
 * 
 * @category Meride
 * @package  Meride\Encoder
 * @author   Toppi Giovanni Manuel @MerideDevTeam <giovanni.toppi@mosai.co>
 * @license  prorietary https://www.meride.tv
 * @link     https://www.meride.tv/docs
 * @todo     Write better documentation about the parameters to pass to the various methods
 */
namespace Meride\Encoder;
/**
 * Class for managing interactions with the encoding system
 */
class Encoder
{
    /**
     * The authorizazion key
     */
    private $key;
    /**
     * The host of the encoder
     */
    private $host;

    /**
     * Encoder constructor
     * @param string $key The authorizazion key
     * @param string $host The host of the encoder
     */
    public function __construct($key, $host)
    {
        $this->key = $key;
        $this->host = preg_replace('{/$}', '', $host);
        $this->checkConnection();
    }
    /**
     * Performs a network request to the encoder service
     * @param string $type  HTTP verb
     * @param string $path  Path of the service to follow
     * @param array $params The request parameters
     * @throws \Exception   When a network/CURL error occurs
     * @return string|object
     */
    private function request($type, $path, $params = array())
    {
        $ch = curl_init();
        if (FALSE === $ch)
        {
            throw new \Exception('failed to initialize curl');
        }
        $headers = array(
            'access-key: ' . $this->key,
        );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $this->host . $path);
        if ($params)
        {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        $content = curl_exec($ch);
        if (FALSE === $content)
        {
            throw new \Exception(curl_error($ch), curl_errno($ch));
        }
        curl_close($ch);
        if ($content)
        {
            $res = json_decode($content);
        }
        return (isset($res) && $res) ? $res : $content;
    }
    /**
     * Checks if the connection to the encoder is set up correctly.
     * @throws \Exception   When the API key is not valid
     * @return boolean
     */
    public function checkConnection()
    {
        $request = $this->request('GET', '/api/check');
        if (!isset($request->status) || $request->status !== "success")
        {
            throw new \Exception('Api Key Non valida');
        }
        return true;
    }
    /**
     * Adds a single video to the encoder queue
     * @param array $params Can be passed these params: slot, customer_name, video_file, video_name, video_path, path_ftp, callback_url
     * @throws \Exception   When video_file is not present
     * @return string|object
     */
    public function addVideo($params)
    {
        if (!isset($params["video_file"]))
        {
            throw new \Exception('Video File Assente');
        }
        if (isset($params["module_parameters"]))
        {
            $params["module_parameters"] = json_encode($params["module_parameters"]);
        }
        $params["video_file"] = new \CURLFile($params["video_file"]);
        return $this->request('POST', '/api/add/video', $params);
    }
    /**
     * Adds a video to the encoder queue for a multiple renditions process
     * @param string $video_file The path to the file to be encoded
     * @param array $video An array containing where each element is an array containing: slot, customer_name, video_file, video_name, video_path, path_ftp, callback_url
     * @return string|object
     */
    public function addVideoMultiple($video_file, $videos)
    {
        foreach ($videos as $key => $video)
        {
            if (isset($video["module_parameters"]))
            {
                $videos[$key]["module_parameters"] = json_encode($video["module_parameters"]);
            }
        }
        $params = [
            "video_file" => new CURLFile($video_file),
            "videos" => json_encode($videos)
        ];
        return $this->request('POST', '/api/add/video/multiple', $params);
    }
    /**
     * Retrives infos about a video
     * @param string $id    The ID of the video
     * @return string|object
     */
    public function getVideo($id)
    {
        return $this->request('POST', '/api/get/video/', ["id" => (int)$id]);
    }
    /**
     * Retrives infos about a slot
     * @param string $slot    The name of the slot
     * @return string|object
     */
    public function getVideos($slot)
    {
        return $this->request('POST', '/api/get/videos', ["slot" => $slot]);
    }
    /**
     * Returns the pending videos inside a slot
     * @param string $slot    The name of the slot
     * @return string|object
     */
    public function getPendingVideos($slot)
    {
        return $this->request('POST', '/api/get/videos/pending', ["slot" => $slot]);
    }
    /**
     * Adds a slot to the system
     * @param string $params    The slot settings: 
     * @return string|object
     */
    public function addSlot($params)
    {
        if (isset($params["module_parameters"]))
        {
            $params["module_parameters"] = (string)json_encode($params["module_parameters"]);
        }
        return $this->request('POST', '/api/add/slot', $params);
    }
    /**
     * Edits the settings of a slot
     * @param string $params    The slot settings: 
     * @return string|object
     */
    public function editSlot($params)
    {
        if (isset($params["module_parameters"]))
        {
            $params["module_parameters"] = (string)json_encode($params["module_parameters"]);
        }
        return $this->request('POST', '/api/edit/slot', $params);
    }
    /**
     * Removes a slot
     * @param string $params    The slot params 
     * @return string|object
     */
    public function removeSlot($params)
    {
        return $this->request('DELETE', '/api/remove/slot', $params);
    }
    /**
     * Retrieves all the slots
     * @return string|object
     */
    public function getSlots()
    {
        return $this->request('POST', '/api/get/slots');
    }
    /**
     * Gets info about a slot
     * @param array $params     The params of the slot request.
     * @return void
     */
    public function getSlot($params)
    {
        return $this->request('POST', '/api/get/slot', $params);
    }
    /**
     * Retrieves all the modules
     * @return string|object
     */
    public function getModules()
    {
        return $this->request('POST', '/api/get/modules');
    }
}
