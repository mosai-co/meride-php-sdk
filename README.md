# General PHP libraries for Meride

General PHP libraries for Meride that interacts with Meride's APIs, with Meride's encoders and gives web functionalities.

The packages included are:

- **Api** for communications with Meride's APIs
- **Web** for producing HTML code (eg. integrating an embed)
- **Storage** for interacting with the storage


### INSTALLATION

`composer require mosai-co/meride-php-sdk`

it will require the [public composer repository](https://packagist.org/packages/mosai-co/meride-php-sdk)

The minimum requirement for PHP will be version 7.2

### DEVELOPMENT INSTALLATION

- clone the repo and go the the project folder
- run `composer install`
- if you are not using a system that automatically includes composer autoload, include it in your page (e.g. `include './vendor/autoload.php'`) and you are ready to use the library.

### TESTING

1. create MERIDE_URL environment on the system with `export MERIDE_URL="{{URL}}"` where {{URL}} will be the CMS to test (e.g. https://cms.meride.tv/yourclientid)
2. create MERIDE_AUTH_CODE environment on the system with `export MERIDE_AUTH_CODE="{{AUTH_CODE}}"` where {{AUTH_CODE}} will be the authorization code to call the API
3. create MERIDE_STORAGESERVICE_URL environment on the system with `export MERIDE_STORAGESERVICE_URL="{{STORAGESERVICE_URL}}"` where {{STORAGESERVICE_URL}} is the address (base URL) of the storage service
4. create MERIDE_AUTH_USER environment on the system with `export MERIDE_AUTH_USER="{{AUTH_USER}}"` where {{AUTH_USER}} is the desired client ID
5. create MERIDE_VIDEO_ID environment on the system with `export MERIDE_VIDEO_ID="{{VIDEO_ID}}"` where {{VIDEO_ID}} is the desired video ID that will be tested (it must exist on the test platform)
6. create MERIDE_STORAGE_PROTOCOL enviroment specifying if the storage HTTP protocol for the storage URL is http or https
7. launch `./testall.sh` from the root directory of the project

### DOCUMENTATION GENERATION

To produce an internal documentation phpdoc is required on the system.

To see the documentation of the project launch this from the command line and access to the docs/ directory with a browser

`phpdoc -d ./src/ -t ./docs`

or launch the script `./generatedocs.sh`

### PUBLIC DOCUMENTATION

To read the public documentation go to the [Meride's documentation page](https://www.meride.tv/docs/section.html?route=sdk__php/index)


### INITIALIZATION

```php



use Meride\Api;

// substitute with the URL of your own CMS path
define('MERIDE_URL', "https://cms.meride.tv/CLIENT_NAME");
// define which API version to use (default v2)
define('MERIDE_VERSION', 'v2');
// define your access token, visible inside the CMS
define('MERIDE_ACCESS_TOKEN', 'MERIDE_AUTH_CODE');

// instantiate an API object
$merideApi = new Api(MERIDE_ACCESS_TOKEN, MERIDE_URL, MERIDE_VERSION);



```

### GET request (single)


```php

$video = $merideApi->get('video', 1234);
echo $video->title;

```

### GET request (collection)


```php

$videoCollection = $merideApi->all('video');
// numbers of records in the collection
$videoCount = $videoCollection->count();
// iterating on the records
foreach($videoCollection as $video) {
    echo $video->title."\r\n";
}

```

### ERROR MANAGEMENT

```php

// Reading a non-existing video
$video = $merideApi->read('video', 9999);
if ($video->hasErrors())
{
    // some error occured
    $apiResponse = $video->getApiResponse();
    $error = $apiResponse->error;
    if ($apiResponse->httpCode == 404)
    {
        echo "Record not found";
    }
    else
    {
        echo "\r\nError message: ".$error->message;
        echo "\r\nError code: ".$error->errorCode;
    }
}
else
{
    if ($video->isEmpty())
    {
        echo "No data available as the response is empty";
    }
    else
    {
        echo "The video has ID ".$video->id." and title ".$video->title;
    }
}

```

### WEB FEATURES

generate Meride's div

```php
use Meride\Web\Embed as Embed;
echo Embed::div(array(
    'embedID' => '1594',
    'clientID' => 'webink',
    'width' => '640',
    'height' => '400',
    'bulkLabel' => 'testLabel',
    'autoPlay' => 'true',
    'responsive' => 'true'
));

```

generate Meride's iframe

```php
use Meride\Web\Embed as Embed;
echo Embed::iframe(array(
    'embedID' => '1594',
    'clientID' => 'webink',
    'width' => '640',
    'height' => '400',
    'bulkLabel' => 'testLabel'
));

```
