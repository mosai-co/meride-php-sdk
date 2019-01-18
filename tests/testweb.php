<?php
require_once __DIR__.'/../vendor/autoload.php';

use Meride\Web\Embed as Embed;


/*echo Embed::iframe(array(
    'embedID' => '1594',
    'clientID' => 'webink',
    'width' => '640',
    'height' => '400',
    'bulkLabel' => 'testLabel'
));*/

/*echo Embed::div(array(
    'embedID' => '1594',
    'clientID' => 'webink',
    'width' => '640',
    'height' => '400',
    'bulkLabel' => 'testLabel',
    'autoPlay' => 'true',
    'responsive' => 'true'
));*/

echo Embed::ampiframe(array(
    'embedID' => '1594',
    'clientID' => 'webink',
    'width' => '640',
    'height' => '400',
    'bulkLabel' => 'testLabel'
));