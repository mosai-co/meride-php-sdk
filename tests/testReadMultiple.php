<?php
require_once __DIR__.'/../vendor/autoload.php';

use Meride\Api;

define('MERIDE_URL', "http://dev7.meride.tv/webink");
define('MERIDE_VERSION', 'v2');
define('MERIDE_ACCESS_TOKEN', getenv('MERIDE_AUTH_CODE'));


$merideApi = new Api(MERIDE_ACCESS_TOKEN, MERIDE_URL, MERIDE_VERSION);

$videos = $merideApi->all('video', [
    'search_page' => 1,
    'search_for_page' => 2
]);

echo "===\r\n";
echo $videos->count();
echo "\r\n===\r\n";
echo var_dump($videos[0]->title);
