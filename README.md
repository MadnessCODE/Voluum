# Voluum SDK For PHP
[![Build Status](https://travis-ci.org/MadnessCODE/Voluum.svg?branch=master)](https://travis-ci.org/MadnessCODE/Voluum)
[![Latest Stable Version](https://poser.pugx.org/madnesscode/voluum/v/stable)](https://packagist.org/packages/madnesscode/voluum)
[![Total Downloads](https://poser.pugx.org/madnesscode/voluum/downloads)](https://packagist.org/packages/madnesscode/voluum)
[![License](https://poser.pugx.org/madnesscode/voluum/license)](https://packagist.org/packages/madnesscode/voluum)

This repository contains the open source PHP SDK that allows you to access the Voluum REST API

## Installation

The Voluum PHP SDK can be installed with [Composer](https://getcomposer.org/). Run this command:

```sh
composer require madnesscode/voluum
```

## Usage

> **Note:** This version of the Voluum SDK for PHP requires PHP 5.6 or greater.

Example

```php
use MadnessCODE\Voluum;
```
Use voluum account email and password for credentials:
```php
$email = 'test@example.com';
$password = 'test';

$client = new Voluum\Client\API(new Voluum\Auth\PasswordCredentials($email, $password));

$report_api = new Voluum\API($client);
```

Or use access id and key:
```php
$access_key_id = "access_key_id";
$access_key = "access_key";

$client = new Voluum\Client\API(new Voluum\Auth\AccessKeyCredentials($access_key_id, $access_key));

$report_api = new Voluum\API($client);
```

How to get report:
```php
$result = $report_api->get("report", [
   "from" => date("Y-m-d"),
   "to" => date("Y-m-d"),
   "groupBy" => "campaign"
]);

//Get result as json
echo $result->getJson();

//Get result as object
var_dump($result->getData());
```

Create new lander:
```php
$result = $report_api->post("lander", [
   "namePostfix" => "Test",
   "url" => "http://example.com"
]);
```

Edit lander:
```php
$result = $report_api->put("lander/xxxxx-xxxxxx-xxxxxx-xxxxx", [
   "namePostfix" => "Test 1",
   "url" => "http://example.com"
]);
```

Delete lander:
```php
$result = $report_api->delete("lander", [
   "ids" => "xxxxx-xxxxxx-xxxxxx-xxxxxx"
]);
```

Complete documentation is available [here](https://developers.voluum.com/).

## License
Please see the [license file](https://github.com/MadnessCODE/Voluum/blob/master/LICENSE) for more information.