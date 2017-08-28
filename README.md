# Laravel Multilog

This project is a fork of [karlomikus/multilog](https://github.com/karlomikus/multilog)

[![Build Status](https://travis-ci.org/karlomikus/multilog.svg?branch=master)](https://travis-ci.org/karlomikus/multilog)
[![Latest Stable Version](https://poser.pugx.org/karlomikus/multilog/v/stable)](https://packagist.org/packages/karlomikus/multilog)
[![License](https://poser.pugx.org/karlomikus/multilog/license)](https://packagist.org/packages/karlomikus/multilog)

Easily add multiple Monolog channels to your Laravel or Lumen application.

## Install

Via Composer

``` bash
composer require karlomikus/multilog
```

Or add the package to your composer file:

``` json
"karlomikus/multilog": "2.*"
```

Next register service provider and facade in your `config/app.php` file:

``` php
// Service provider
Karlomikus\Multilog\MultilogServiceProvider::class
// Facade (optional)
'Multilog' => Karlomikus\Multilog\Facade::class
```

## Configuration

With Laravel, you can publish the configuration file with:

``` bash
php artisan vendor:publish
```

If you are using Lumen, please copy the example of
[configuration file](src/Multilog/config/multilog.php)
to your application `config` directory. Then add the following line to
`bootstrap/app.php`:

```php
$app->configure('multilog');
```

All your channels are defined in `config/multilog.php` file.

By default you have two channels (request and info):

``` php
// Request channel
'request' => function ($channel) {
    $logger = new Logger($channel);
    ...
    return $logger;
},
// Info channel
'info' => function ($channel) {
    $logger = new Logger($channel);
    ...
    return $logger;
}
```

## Usage

Using dependency injection:

``` php
use Karlomikus\Multilog\Contracts\MultilogInterface;

private $multilog;

public function __construct(MultilogInterface $multilog)
{
    $this->multilog = $multilog;

    $this->multilog->channel('request')->error('Error here...');
}
```

Using facade:

``` php
Multilog::channel('channel-name')->info('Information here...');

// Channel shorthand is also available
Multilog::c('channel-name')->warning('Warning here...');
```

## Redirecting Log facade calls to a Multilog channel

If you wanna make sure Multilog is used, you can redirect all Log facade calls
to Multilog by adding the following service provider:

```php
// Service provider
Karlomikus\Multilog\LogServiceProvider::class
```

Then configure the default channel in your `config/multilog.php` file:

```php
'defaultChannel' => 'global',
'channels' => [
    'request' => function ($channel) {
        $logger = new Logger($channel);
        ...
        return $logger;
    },
]
```

## Change log

### [2.0.0] - 2017-08-28

* Change configuration format to use closures
* Compatibility with Lumen
* Possibility to redirect `Log` calls to a `Multilog` default channel

### [1.0.0] - 2016-03-06

* Initial release

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
