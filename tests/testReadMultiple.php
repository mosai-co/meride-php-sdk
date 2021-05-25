<?php
require_once __DIR__.'/../vendor/autoload.php';

use Meride\Api;

define('MERIDE_URL', getenv('MERIDE_URL'));
define('MERIDE_VERSION', 'v2');
define('MERIDE_ACCESS_TOKEN', getenv('MERIDE_AUTH_CODE'));

echo "\r\n";


$merideApi = new Api(MERIDE_ACCESS_TOKEN, MERIDE_URL, MERIDE_VERSION);

$videos = $merideApi->all('video', [
    'search_page' => 2,
    'search_for_page' => 10
]);
if ($videos->hasErrors()) {
    $apiResponse = $videos->getApiResponse();
    echo "\r\nErrors found\r\n";
    echo $apiResponse->error;
};
if ($videos->isEmpty()) {
    echo "\r\nno video found";
} else {
    $countVideos = $videos->count();
    echo "===\r\n";
    echo $countVideos;
    echo "\r\n===\r\n";
    if ($countVideos > 0) {
        echo "title first video: ".$videos[0]->title;
    }
}
echo "\r\nFROM: ".$videos->from();
echo "\r\nTOTAL: ".$videos->total();
echo "\r\n\r\n";

