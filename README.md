# General PHP libraries for Meride

General PHP libraries for Meride that interacts with Meride's APIs

## USE

### INITIALIZATION

```php

use Meride\Api;

define('MERIDE_URL', "http://URL_TO_REST_SERVICE");
define('MERIDE_ACCESS_TOKEN', "ACCESS_TOKEN");

$merideApi = new Api(MERIDE_ACCESS_TOKEN, MERIDE_URL);


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