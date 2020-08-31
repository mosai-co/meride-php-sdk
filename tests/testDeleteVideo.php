<?php

require_once __DIR__.'/../vendor/autoload.php';

use Meride\Api;
use Meride\MerideCollection;
use Meride\Model\Meride;

define('MERIDE_URL', "http://dev7.meride.tv/webink");
define('MERIDE_VERSION', 'v2');
define('MERIDE_ACCESS_TOKEN', getenv('MERIDE_AUTH_CODE'));

$merideApi = new Api(MERIDE_ACCESS_TOKEN, MERIDE_URL, MERIDE_VERSION);

$videoResponse = $merideApi->read('video', 1141);

$videoDeleteResponse = $merideApi->delete('video', $videoResponse->id);

echo var_dump($videoDeleteResponse);