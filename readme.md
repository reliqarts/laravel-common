# Laravel Simple Commons

Tiny Laravel 5 package to provide simple, somewhat commonly needed helper functions.

[![Built For Laravel](https://img.shields.io/badge/built%20for-laravel-red.svg?style=flat-square)](http://laravel.com)
[![License](https://poser.pugx.org/reliqarts/laravel-simple-commons/license?format=flat-square)](https://packagist.org/packages/reliqarts/laravel-simple-commons)
[![Latest Stable Version](https://poser.pugx.org/reliqarts/laravel-simple-commons/version?format=flat-square)](https://packagist.org/packages/reliqarts/laravel-simple-commons)
[![Latest Unstable Version](https://poser.pugx.org/reliqarts/laravel-simple-commons/v/unstable?format=flat-square)](//packagist.org/packages/reliqarts/laravel-simple-commons)

## Installation & Use

Install via composer:

```php
composer require reliqarts/simple-commons
```

Add service provider:

```php
ReliQArts\SimpleCommons\SimpleCommonsServiceProvider::class,
```

Use helpers anywhere.

```php
SCVersionHelper::getVersion()
```

All done! :beers: