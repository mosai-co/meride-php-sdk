<?php

require_once __DIR__.'/../vendor/autoload.php';

use Meride\Api;
use Meride\MerideCollection;
use Meride\Model\Meride;

define('MERIDE_URL', getenv('MERIDE_URL'));
define('MERIDE_VERSION', 'v2');
define('MERIDE_ACCESS_TOKEN', getenv('MERIDE_AUTH_CODE'));

$merideApi = new Api(MERIDE_ACCESS_TOKEN, MERIDE_URL, MERIDE_VERSION);

// copy a renamed video to avoid name duplication and error in the CMS
$randNum = rand(0, 999999);
$videoOrigin = __DIR__."/assets/small.mp4";
$videoCopyRenamed = __DIR__."/assets/small".$randNum.".mp4";
copy($videoOrigin, $videoCopyRenamed);

$video = new \CurlFile($videoCopyRenamed);

$videoResponse = $merideApi->create('video', array(
    'title' => "Test video ".$randNum,
    'video' => $video
));

unlink($videoCopyRenamed);

echo "CONTENT\r\n========\r\n";
echo "<pre>";
echo var_dump($videoResponse);
echo "</pre>";