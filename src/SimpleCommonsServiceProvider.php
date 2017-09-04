<?php

namespace ReliQArts\SimpleCommons;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use ReliQArts\SimpleCommons\Http\Middleware\SimpleCommons;

class SimpleCommonsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Assets location.
     */
    protected $assetsDir = __DIR__.'/..';

    /**
     * Publish assets.
     *
     * @return void
     */
    protected function handleAssets()
    {
        $this->publishes([
            "$this->assetsDir/config/config.php" => config_path('simplecommons.php'),
        ], 'config');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // ...
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $loader = AliasLoader::getInstance();

        // Register facades...
        $loader->alias('SCVersionHelper', Helpers\VersionHelper::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
