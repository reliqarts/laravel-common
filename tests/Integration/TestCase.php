<?php

declare(strict_types=1);

namespace ReliqArts\Tests\Integration;

use Orchestra\Testbench\TestCase as BaseTestCase;
use ReliqArts\ServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [ServiceProvider::class];
    }
}
