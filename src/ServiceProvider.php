<?php

declare(strict_types=1);

namespace ReliqArts;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Monolog\Handler\StreamHandler;

class ServiceProvider extends BaseServiceProvider
{
    protected const CONFIG_KEY = 'reliqarts-common';
    protected const ASSET_DIRECTORY = __DIR__ . '/..';
    protected const LOGGER_NAME = 'reliqarts-common-logger';
    protected const LOG_FILENAME = self::CONFIG_KEY;

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->handleConfig();
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton(
            Contracts\ConfigProvider::class,
            function (): Contracts\ConfigProvider {
                return new Services\ConfigProvider(
                    resolve(ConfigRepository::class),
                    self::CONFIG_KEY
                );
            }
        );

        $this->app->singleton(
            Contracts\Filesystem::class,
            Services\Filesystem::class
        );

        $this->app->singleton(
            Contracts\VersionProvider::class,
            Services\VersionProvider::class
        );

        $this->app->singleton(
            Contracts\Logger::class,
            function (): Contracts\Logger {
                $logger = new Services\Logger($this->getLoggerName());
                $logFile = storage_path(sprintf('logs/%s.log', self::LOG_FILENAME));
                $logger->pushHandler(new StreamHandler($logFile, Services\Logger::DEBUG));

                return $logger;
            }
        );
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

    /**
     * @return string
     */
    protected function getAssetDirectory(): string
    {
        return static::ASSET_DIRECTORY;
    }

    /**
     * @return string
     */
    protected function getConfigKey(): string
    {
        return static::CONFIG_KEY;
    }

    /**
     * @return string
     */
    protected function getLogFilename(): string
    {
        return static::LOG_FILENAME;
    }

    /**
     * @return string
     */
    protected function getLoggerName(): string
    {
        return static::LOGGER_NAME;
    }

    /**
     * Register Configuration.
     */
    protected function handleConfig(): void
    {
        $configKey = $this->getConfigKey();
        $config = sprintf('%s/config/config.php', $this->getAssetDirectory());

        // merge config
        $this->mergeConfigFrom($config, $configKey);

        $this->publishes(
            [$config => config_path(sprintf('%s.php', $configKey))],
            sprintf('%s-config', $configKey)
        );
    }
}
