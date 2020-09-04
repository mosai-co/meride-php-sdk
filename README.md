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

1. create MERIDE_URL environment on the system with `export MERIDE_URL="{{URL}}"` where {{URL}} will be the CMS to test (e.g. http://dev7.meride.tv/webink)
2. create MERIDE_AUTH_CODE environment on the system with `export MERIDE_AUTH_CODE="{{AUTH_CODE}}"` where {{AUTH_CODE}} will be the authorization code to call the API
3. create MERIDE_STORAGESERVICE_URL environment on the system with `export MERIDE_STORAGESERVICE_URL="{{STORAGESERVICE_URL}}"` where {{STORAGESERVICE_URL}} is the address (base URL) of the storage service
4. create MERIDE_AUTH_USER environment on the system with `export MERIDE_AUTH_USER="{{AUTH_USER}}"` where {{AUTH_USER}} is the desired client ID
5. create MERIDE_VIDEO_ID environment on the system with `export MERIDE_VIDEO_ID="{{VIDEO_ID}}"` where {{VIDEO_ID}} is the desired video ID that will be tested (it must exist on the test platform)
5. launch `./testall.sh` from the root directory of the project

### DOCUMENTATION GENERATION

To produce an internal documentation phpdoc is required on the system.

To see the documentation of the project launch this from the command line and access to the docs/ directory with a browser

`vendor/bin/apigen generate src --destination docs`

or launch the script `./generatedocs.sh`

### INITIALIZATION

```php

use Meride\Api;

define('MERIDE_URL', "https://URL_TO_REST_SERVICE");
define('MERIDE_AUTH_CODE', "AUTH_CODE");

$merideApi = new Api(MERIDE_AUTH_CODE, MERIDE_URL);


```

### GET request

```php
$videoResponse = $merideApi->get('configuration');
```

or 

```php
$videoResponse = $merideApi->get('configuration', $id);
```

read raw response

```php
$videoResponse->content
```

read JSON response

```php
$videoResponse->jsonContent
```

read number of elements in the response

```php
$videoResponse->count()
```

read error

```php
$videoResponse->error
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
