# Laravel Common

Reliq Arts' base/common classes and functions for Laravel 5.

[![Built For Laravel](https://img.shields.io/badge/built%20for-laravel-red.svg?style=flat-square)](http://laravel.com)
[![Build Status (all)](https://img.shields.io/travis/reliqarts/laravel-common.svg?style=flat-square)](https://travis-ci.org/reliqarts/laravel-common)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/reliqarts/laravel-common.svg?style=flat-square)](https://scrutinizer-ci.com/g/reliqarts/laravel-common/)
[![Codecov](https://img.shields.io/codecov/c/github/reliqarts/laravel-common.svg?style=flat-square)](https://codecov.io/gh/reliqarts/laravel-common)
[![License](https://poser.pugx.org/reliqarts/laravel-common/license?format=flat-square)](https://packagist.org/packages/reliqarts/laravel-common)
[![Latest Stable Version](https://poser.pugx.org/reliqarts/laravel-common/version?format=flat-square)](https://packagist.org/packages/reliqarts/laravel-common)
[![Latest Unstable Version](https://poser.pugx.org/reliqarts/laravel-common/v/unstable?format=flat-square)](//packagist.org/packages/reliqarts/laravel-common)

## Installation & Use

Install via composer:

```php
composer require reliqarts/laravel-common
```

Add service provider:

```php
ReliqArts\ServiceProvider::class,
```

Use anywhere throughout your application. e.g.

```php
$versionProvider = resolve(ReliqArts\Contracts\VersionProvider::class);
$versionProvider->getVersion();
```

All done! :beers:
