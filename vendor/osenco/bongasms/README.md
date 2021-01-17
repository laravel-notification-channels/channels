# Bonga SMS
Bonga SMS PHP SDK

## Installation
Install via composer

```cmd
composer require osenco/bongasms
```

## Instantiation
Pass the apiClientID, key, secret in the class constructor

```php
$sms = new Osen\Bonga\Sms($apiClientID, $key, $secret);
```

## Usage
### Send SMS
```php
$sms->send($phone, $message);
```
