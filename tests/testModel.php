<?php
require_once __DIR__.'/../vendor/autoload.php';

use Meride\Api;
use Meride\MerideCollection;
use Meride\Model\Meride;

define('MERIDE_URL', getenv('MERIDE_URL'));
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
$bulkConfigurations = $merideApi->all('bulk');
foreach($bulkConfigurations as $bulk) {
    echo $bulk->id."\r\n";
}
print_r($bulkConfigurations[0]->title);
die();
$response = $merideApi->get('video', 919);
print_r($response);
echo "\r\n========\r\n";
print_r($response->getApiResponse());
echo "\r\n========\r\n";
print_r($response['id']);
//$video = Meride::factory('video', $videoResponse->jsonContent);

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

/*echo "CONTENT\r\n========\r\n";
echo "<pre>";
echo var_dump($videoResponse->content);
echo "</pre>";
echo "JSON CONTENT\r\n========\r\n";
echo "<pre>";
echo var_dump($videoResponse->jsonContent);
echo "</pre>";
echo "JSON CONTENT COUNT\r\n========\r\n";
echo "<pre>";
echo var_dump($videoResponse->count());
echo "</pre>";
echo "\r\n========\r\ERROR\r\n========\r\n";
echo "<pre>";
echo var_dump($videoResponse->error);
echo "</pre>";*/
