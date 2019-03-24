# Laravel Common

Reliq Arts' base/common classes and functions for Laravel 5.

[![Built For Laravel](https://img.shields.io/badge/built%20for-laravel-red.svg?style=flat-square)](http://laravel.com)
[![CircleCI (all branches)](https://img.shields.io/circleci/project/github/reliqarts/laravel-common/master.svg?style=flat-square)](https://circleci.com/gh/reliqarts/laravel-common/tree/master)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/reliqarts/laravel-common.svg?style=flat-square)](https://scrutinizer-ci.com/g/reliqarts/laravel-common/)
[![Codecov](https://img.shields.io/codecov/c/github/reliqarts/laravel-common.svg?style=flat-square)](https://codecov.io/gh/reliqarts/laravel-common)
[![License](https://poser.pugx.org/reliqarts/laravel-common/license?format=flat-square)](https://packagist.org/packages/reliqarts/laravel-simple-commons)
[![Latest Stable Version](https://poser.pugx.org/reliqarts/laravel-common/version?format=flat-square)](https://packagist.org/packages/reliqarts/laravel-simple-commons)
[![Latest Unstable Version](https://poser.pugx.org/reliqarts/laravel-common/v/unstable?format=flat-square)](//packagist.org/packages/reliqarts/laravel-simple-commons)

## Installation & Use

Install via composer:

```php
composer require reliqarts/common
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