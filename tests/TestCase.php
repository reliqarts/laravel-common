<?php

declare(strict_types=1);

namespace ReliqArts\Tests;

use Illuminate\Foundation\Application;
use ReliqArts\ServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Get package providers.
     *
     * @param  Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [ServiceProvider::class];
    }
}
