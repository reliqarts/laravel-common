# Laravel Common

Reliq Arts' base/common classes and functions for Laravel 6+.

[![Built For Laravel](https://img.shields.io/badge/built%20for-laravel-red.svg?style=flat-square)](http://laravel.com)
[![Test](https://github.com/reliqarts/laravel-common/actions/workflows/test.yml/badge.svg)](https://github.com/reliqarts/laravel-common/actions/workflows/test.yml)
[![Maintainability](https://api.codeclimate.com/v1/badges/e7b684b6903c5fc5ba34/maintainability)](https://codeclimate.com/github/reliqarts/laravel-common/maintainability)
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
- NonWWW middleware: Tiny middleware to redirect all www requests to non-www counterparts.

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
Use NonWWW middleware in Kernel. i.e.

```php
'web' => [
    // ...

    \ReliqArts\NonWWW\Http\Middleware\NonWWW::class,

    // ...
],
```

All done! :beers:
