<?php
require_once __DIR__.'/../vendor/autoload.php';

use Meride\Encoder\Encoder;

$encoder = new Encoder('LjeR@}l8GvH8Ym60wFv>', 'http://localhost:7000');

$encoderParams = array(
    "slot" => 'webink_mp4',
    "customer_name" => 'webink',
    "video_file" => '/var/www/html/mosaico/test_del12.mp4',
    "video_name" => 'test_del12a.mp4'
);

try
{
    $encoder->addVideo($encoderParams);
}
catch(\Exception $e)
{
    print_r($e);
}
