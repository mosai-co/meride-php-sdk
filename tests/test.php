<?php
require_once __DIR__.'/../vendor/autoload.php';

use Meride\Api;

define('MERIDE_URL', "http://dev7.meride.tv/webink");
define('MERIDE_VERSION', 'v2');
define('MERIDE_ACCESS_TOKEN', getenv('MERIDE_AUTH_CODE'));


$merideApi = new Api(MERIDE_ACCESS_TOKEN, MERIDE_URL, MERIDE_VERSION);



/*$videoResponse = $merideApi->request('getEmbed', array(
    'id' => '1211'
));*/
/*
$video = $merideApi->request('getVideo', array(
    'id' => $embed->video->id
));*/
/*$video = $merideApi->update('video', 860, array(
    'titolo' => "Test update2"
));*/
//$video = $merideApi->delete('video', 735);
//$videoResponse = $merideApi->get('category');
$videoResponse = $merideApi->get('video', 1128);

echo var_dump($videoResponse->hasErrors());
echo var_dump($videoResponse->getApiResponse());
echo var_dump($videoResponse->isEmpty());
die();

/*$videoResponse = $merideApi->create('categoria', array(
    'titolo' => "Test update8",
    'father_id' => 1,
    'descrizione' => 'Lorem ispum'
));*/

//$info = pathinfo("./test_del12_a.mp4");
//print_r($info);
//$video->setPostFileName("test_del12_b.mp4");
//$info = pathinfo($video_url);
//$video->setPostFileName(uniqid() . ((isset($info["extension"])) ? "." . $info["extension"] : null));
//$video->setPostFileName();
/*$video_url = "/var/www/html/mosaico/meride-php-api/tests/test_del12_b.mp4";
$video = new \CurlFile($video_url);
$videoResponse = $merideApi->create('video', array(
    'title' => "Test video",
    'video' => $video
));*/

/**
 * 
 * To create a video pass a 'video' attribute with a value like
 * 
 * $file = new \CurlFile($params["video"]);
 * $info = pathinfo($params["video"]);
 * $file->setPostFileName(uniqid() . ((isset($info["extension"])) ? "." . $info["extension"] : null));
 * $param['video'] = $file;
 */


echo var_dump($videoResponse);
echo "\r\n========\r\n";
echo "title: ".$videoResponse->title;