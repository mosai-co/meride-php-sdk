<?php

namespace Meride\Encoder;
/**
 * Classe per la gestione delle interazioni con il sistema di encoding
 */
class Encoder
{
    private $key, $host;

    public function __construct($key, $host)
    {
        $this->key = $key;
        $this->host = preg_replace('{/$}', '', $host);
        $this->checkConnection();
    }

    public function request($type, $path, $params = array())
    {
        $ch = curl_init();

        if (FALSE === $ch)
            throw new Exception('failed to initialize curl');

        $headers = array(
            'access-key: ' . $this->key,
        );


        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $this->host . $path);

        if ($params) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }

        $content = curl_exec($ch);


        if (FALSE === $content)
            throw new Exception(curl_error($ch), curl_errno($ch));

        curl_close($ch);

        if ($content)
            $res = json_decode($content);

        return (isset($res) && $res) ? $res : $content;
    }

    public function checkConnection()
    {
        $request = $this->request('GET', '/api/check');
        if (!isset($request->status) || $request->status !== "success") {
            throw new Exception('Api Key Non valida');
        }

        return true;
    }

    public function addVideo($params)
    {
        if (isset($params["module_parameters"])) {
            $params["module_parameters"] = json_encode($params["module_parameters"]);
        }

        if (!isset($params["video_file"])) throw new Exception('Video File Assente');

        $params["video_file"] = new CURLFile($params["video_file"]);

        return $this->request('POST', '/api/add/video', $params);
    }

    public function addVideoMultiple($video_file, $videos)
    {
        foreach ($videos as $key => $video) {
            if (isset($video["module_parameters"])) {
                $videos[$key]["module_parameters"] = json_encode($video["module_parameters"]);
            }
        }

        $params = [
            "video_file" => new CURLFile($video_file),
            "videos" => json_encode($videos)
        ];

        return $this->request('POST', '/api/add/video/multiple', $params);
    }

    public function getVideo($id)
    {
        return $this->request('POST', '/api/get/video/', ["id" => (int)$id]);
    }

    public function getVideos($slot)
    {
        return $this->request('POST', '/api/get/videos', ["slot" => $slot]);
    }

    public function getPendingVideos($slot)
    {
        return $this->request('POST', '/api/get/videos/pending', ["slot" => $slot]);
    }

    public function addSlot($params)
    {
        if (isset($params["module_parameters"])) {
            $params["module_parameters"] = (string)json_encode($params["module_parameters"]);
        }

        return $this->request('POST', '/api/add/slot', $params);
    }

    public function editSlot($params)
    {
        if (isset($params["module_parameters"])) {
            $params["module_parameters"] = (string)json_encode($params["module_parameters"]);
        }

        return $this->request('POST', '/api/edit/slot', $params);
    }

    public function removeSlot($params)
    {
        return $this->request('DELETE', '/api/remove/slot', $params);
    }

    public function getSlots()
    {
        return $this->request('POST', '/api/get/slots');
    }

    public function getSlot($params)
    {
        return $this->request('POST', '/api/get/slot', $params);
    }

    public function getModules()
    {
        return $this->request('POST', '/api/get/modules');
    }
}
