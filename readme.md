# Laravel Common

Reliq Arts' base/common classes and functions for Laravel 6+.

[![Built For Laravel](https://img.shields.io/badge/built%20for-laravel-red.svg?style=flat-square)](http://laravel.com)
[![Build Status (all)](https://img.shields.io/travis/com/reliqarts/laravel-common?style=flat-square)](https://travis-ci.com/reliqarts/laravel-common)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/reliqarts/laravel-common.svg?style=flat-square)](https://scrutinizer-ci.com/g/reliqarts/laravel-common/)
[![Codecov](https://img.shields.io/codecov/c/github/reliqarts/laravel-common.svg?style=flat-square)](https://codecov.io/gh/reliqarts/laravel-common)
[![License](https://poser.pugx.org/reliqarts/laravel-common/license?format=flat-square)](https://packagist.org/packages/reliqarts/laravel-common)
[![Latest Stable Version](https://poser.pugx.org/reliqarts/laravel-common/version?format=flat-square)](https://packagist.org/packages/reliqarts/laravel-common)
[![Latest Unstable Version](https://poser.pugx.org/reliqarts/laravel-common/v/unstable?format=flat-square)](//packagist.org/packages/reliqarts/laravel-common)

## Features/Contents
- Config provider implementation
    - uses `Illuminate\Contracts\Config\Repository`
    - allows easy access to config keys under specific (package) 'namespace'
- `Illuminate\Filesystem` implementation
    - Specifically changing the behaviour of `deleteDirectory()`
- [Monolog](https://github.com/Seldaek/monolog) Logger
- Result Object (simple DTO)
- Version Provider
- Sitemap generation command
    - via Spatie's [Laravel Sitemap](https://github.com/spatie/laravel-sitemap)

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
$versionProvider = resolve(ReliqArts\Contract\VersionProvider::class);
$versionProvider->getVersion();
```

All done! :beers:
