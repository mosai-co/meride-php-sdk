<?php
require_once __DIR__.'/../vendor/autoload.php';

use Meride\Api;
use Meride\Storage\Tus\Client as TusClient;
use Meride\Storage\Tus\Token;

define('MERIDE_URL', "http://dev7.meride.tv/webink");
define('MERIDE_VERSION', 'v2');
define('MERIDE_ACCESS_TOKEN', getenv('MERIDE_AUTH_CODE'));

$videoOrigin = __DIR__."/assets/small.mp4";



//$merideApi = new Api(MERIDE_ACCESS_TOKEN, MERIDE_URL, MERIDE_VERSION);
$tokenGenerator = new Token('webink', getenv('MERIDE_AUTH_CODE'));
try {
    $token = $tokenGenerator->generate();
} catch(\Exception $e) {
    die($e->getMessage()."\r\n");
}

$tusClient = new TusClient(
    $token
);
$tusClient->setProtocol("http");
$uploadUrl = $tusClient->upload($videoOrigin, 'smaller.mp4');
if ($uploadUrl !== false) {
    echo "\r\nUploaded ".$uploadUrl;
    $extractedUrl = $tusClient->extractURL($uploadUrl);
    echo "\r\nVideo URL ".$extractedUrl."\r\n\r\n";
    $merideApi = new Api(MERIDE_ACCESS_TOKEN, MERIDE_URL, MERIDE_VERSION);
    $videoResponse = $merideApi->create('video', array(
        'title' => "Test video",
        'video' => $extractedUrl
    ));
    echo var_dump($videoResponse);
} else {
    echo "Some error occured";
}

