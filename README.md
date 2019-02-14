# Uctenkovka

PHP library for communication with Uctenkovka (EET Czech Republic competition with receipts). See [Uctenkovka.cz](https://www.uctenkovka.cz/).
PHP 7.1+ is required.

Authors:
 - Petr Suchy ([xsuchy09](mailto:suchy@wamos.cz?subject=GitHub%20-%20Uctenkovka) - [WAMOS.cz](https://www.wamos.cz))

## Overview

Uctenkovka library can send receipts directly into Uctenkovka competition.

## Installation (via composer)

[Get composer](http://getcomposer.org/doc/00-intro.md) and add this in your requires section of the composer.json:

```
{
    "require": {
        "xsuchy09/uctenkovka": "*"
    }
}
```

and then

```
composer install
```

or just
```
composer require xsuchy09/uctenkovka
```

## Usage

You have to have own certificates to communicate with Uctenkovka. See [etrzby](https://www.etrzby.cz/) for more info. In czech language there are two important documents:
- [Conditions](http://www.etrzby.cz/assets/cs/prilohy/Uctenkovka-3rd-party-API_podrobnosti-a-podminky-napojeni.pdf)
- [Specification](http://www.etrzby.cz/assets/cs/prilohy/Uctenkovka-3rd-party-API_technicka-specifikace.PDF)

This library covers specification but you have to fill all of the conditions.  

### Basic Example

You can see tests which are included in this repository. To run that tests you need own certs for communication with test server (see [Conditions](http://www.etrzby.cz/assets/cs/prilohy/Uctenkovka-3rd-party-API_podrobnosti-a-podminky-napojeni.pdf) in Czech language). Save your `test_crt.pem` and `test_key.pem` into the `/src/certs/` folder and then you can run the tests which will generate PhpUnit cover report too (100% btw).

#### How to send receipt/request into Uctenkovka

```php
use xsuchy09\Uctenkovka\Request;
use xsuchy09\Uctenkovka\Uctenkovka;

$request = new Request();
$request->setEmail('test@example.com')
	->setPhone('777777777')
	->setBasicConsent(true)
	->setFik('B3A09B52-7C87-4014')
	->setBkp('01234567-89abcdef')
	->setDate('2018-03-17')
	->setTime('16:41')
	->setAmount(4570) // in hellers
	->setSimpleMode(false);

$uctenkovka = new Uctenkovka();
$uctenkovka->setMode(Uctenkovka::MODE_TESTING);
$uctenkovka->setSslCert(__DIR__ . '/../src/certs/test_crt.pem');
$uctenkovka->setSslKey(__DIR__ . '/../src/certs/test_key.pem');
$uctenkovka->send($request);
```

You can set date and time of receipt (request) at once with `\DateTime` object:
```php
$request->setDateTime(DateTime::createFromFormat('Y-m-d H:i:s', $date)); // seconds are optional
```

You can set all of receipt/request values as array - not required param of `Request` constructor:
```php
use xsuchy09\Uctenkovka\Request;

$request = new Request([
    'email' => 'test@example.com',
    'phone' => '777777777',
    'basicConsent' => true,
    'fik' => 'B3A09B52-7C87-4014',
    'bkp' => '01234567-89abcdef',
    'date' => '2018-03-17',
    'time' => '16:41',
    'amount' => 4570,
    'simpleMode' => false
]);
```

More examples can be found in the `/tests/` directory.

Usage is clear and easy. You can [contact me](mailto:suchy@wamos.cz?subject=GitHub%20-%20Uctenkovka) if you need. 
