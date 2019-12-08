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
     * Bootstrap the application events.
     */
    public function boot(): void
    {
        $this->handleConfig();
        $this->handleViews();
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->singleton(
            Contract\ConfigProvider::class,
            function (): Contract\ConfigProvider {
                return new Service\ConfigProvider(
                    resolve(ConfigRepository::class),
                    self::CONFIG_KEY
                );
            }
        );

        $this->app->singleton(
            Contract\Filesystem::class,
            Service\Filesystem::class
        );

        $this->app->singleton(
            Contract\VersionProvider::class,
            Service\VersionProvider::class
        );

        $this->app->singleton(
            Contract\Logger::class,
            function (): Contract\Logger {
                $logger = new Service\Logger($this->getLoggerName());
                $logFile = storage_path(sprintf('logs/%s.log', self::LOG_FILENAME));
                $logger->pushHandler(new StreamHandler($logFile, Service\Logger::DEBUG));

                return $logger;
            }
        );
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    protected function getAssetDirectory(): string
    {
        return static::ASSET_DIRECTORY;
    }

    protected function getConfigKey(): string
    {
        return static::CONFIG_KEY;
    }

    protected function getLogFilename(): string
    {
        return static::LOG_FILENAME;
    }

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

        $this->mergeConfigFrom($config, $configKey);
        $this->publishes(
            [$config => config_path(sprintf('%s.php', $configKey))],
            sprintf('%s-config', $configKey)
        );
    }

    private function handleViews(): void
    {
        $configKey = $this->getConfigKey();
        $viewsDirectory = sprintf('%s/resources/views', $this->getAssetDirectory());

        $this->loadViewsFrom($viewsDirectory, $configKey);
        $this->publishes(
            [$viewsDirectory => base_path(sprintf('resources/views/vendor/%s', $configKey))],
            sprintf('%s-views', $configKey)
        );
    }
}
