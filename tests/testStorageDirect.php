<?php
require_once __DIR__.'/../vendor/autoload.php';

use Meride\Api;
use Meride\Storage\Tus\Client as TusClient;
use Meride\Storage\Tus\Token;

define('MERIDE_URL', getenv('MERIDE_URL'));
define('MERIDE_VERSION', 'v2');
define('MERIDE_ACCESS_TOKEN', getenv('MERIDE_AUTH_CODE'));

$randNum = rand(0, 999999);
$videoOrigin = __DIR__."/assets/small.mp4";
$videoCopyRenamed = __DIR__."/assets/small".$randNum.".mp4";

$tokenGenerator = new Token(getenv('MERIDE_AUTH_USER'), getenv('MERIDE_AUTH_CODE'), getenv('MERIDE_STORAGESERVICE_URL'));
try {
    $token = $tokenGenerator->generate();
} catch(\Exception $e) {
    echo $e->getMessage()."\r\n";
}

$tusClient = new TusClient($token, getenv('MERIDE_STORAGESERVICE_URL'));
$tusClient->setProtocol("https");
try {
    copy($videoOrigin, $videoCopyRenamed);
    $uploadUrl = $tusClient->uploadDirect($videoCopyRenamed);
} catch (\Exception $e) {
    unlink($videoCopyRenamed);
    echo $e->getMessage()."\r\n";
}
if ($uploadUrl !== false) {
    echo "\r\nUploaded ".$uploadUrl;
    $extractedUrl = $tusClient->extractURL($uploadUrl);
    echo "\r\nVideo URL ".$extractedUrl."\r\n\r\n";
    $merideApi = new Api(MERIDE_ACCESS_TOKEN, MERIDE_URL, MERIDE_VERSION);
    $videoResponse = $merideApi->create('video', array(
        'title' => "Test video",
        'video' => $extractedUrl,
        'squeeze_slot' => 'shared'
    ));
    echo var_dump($videoResponse);
} else {
    echo "Some error occured";
}
@unlink($videoCopyRenamed);
