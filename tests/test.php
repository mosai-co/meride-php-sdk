<?php
require_once __DIR__.'/../vendor/autoload.php';

use Meride\Api;

define('MERIDE_URL', "http://dev7.meride.tv/webink");
define('MERIDE_VERSION', 'v2');
define('MERIDE_ACCESS_TOKEN', "EtJaOVJxaNoefxiT6lBNza9h8XhQBis5C15gNFEOiTSog18cczQCAQKyDRf50x");

$merideApi = new Api(MERIDE_ACCESS_TOKEN, MERIDE_URL, MERIDE_VERSION);

/*$embed = $merideApi->request('getEmbed', array(
    'id' => '1211'
));

$video = $merideApi->request('getVideo', array(
    'id' => $embed->video->id
));*/
/*$video = $merideApi->update('video', 860, array(
    'titolo' => "Test update2"
));*/
//$video = $merideApi->delete('video', 735);
$videoResponse = $merideApi->get('category');

/*$videoResponse = $merideApi->create('categoria', array(
    'titolo' => "Test update7",
    'father_id' => 1,
    'descrizione' => 'Lorem ispum'
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


echo "CONTENT\r\n========\r\n";
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
echo "</pre>";