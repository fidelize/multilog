# Laravel Multilog

Easily add multiple monolog channels to your Laravel 5.2.* application.

## Install

Via Composer

``` bash
$ composer require karlomikus/multilog
```

Or add the package to your composer file:

``` json
"karlomikus/multilog": "1.*"
```

Next register service provider and facade in your `config/app.php` file:

``` php
// Service provider
Karlomikus\Multilog\MultilogServiceProvider::class
// Facade (optional)
'Multilog' => Karlomikus\Multilog\Facade::class
```

And finally publish the config file:

``` bash
$ php artisan vendor:publish
```

## Configuration

All your channels are defined in `config/multilog.php` file.

By default you have two channels (request and info):

``` php
// Request channel
'request' => [
    'stream' => 'request.log',
    'daily'  => true,
    'format' => [
        'date'   => 'Y-m-d H:i:s',
        'output' => "[%datetime%] %message% %context% %extra%\n",
    ],
],
// Info channel
'info' => [
    'stream' => 'info.log',
    'daily'  => false
]
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

## Change log

### [1.0.0] - 2016-03-06

* Initial release

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.