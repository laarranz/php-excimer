# PHP Excimer

This package implements the [MediaWiki Excimer](https://www.mediawiki.org/wiki/Excimer) class for ease of use. Generate a json file that you can upload to [speedscope.app](https://www.speedscope.app/) to view the profile.

- [PHP Excimer](#php-excimer)
    - [Installation](#installation)
    - [Usage](#usage)
    - [Links](#links)

### Installation

Require PHP 7.2 and requires installing the excimer extension in your php.ini.

```bash
extension=excimer.so
```

Installation with composer.

```bash
composer require luar/php-excimer
```

### Usage

To use it simply include the following in php file:

Use:
```php
use Luar\Excimer;
```

For profiling
```php
Excimer::trace('/var/profiles/', 'filename', $_SERVER['REQUEST_URI']);
```

For a save a CSV file with time and memory used.
```php
Excimer::startTimer('/var/metrics/', 'php-memory-usage');
```

### Links
- https://www.mediawiki.org/wiki/Excimer
- https://www.speedscope.app/
