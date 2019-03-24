<?php

namespace ReliqArts;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Monolog\Handler\StreamHandler;

class ServiceProvider extends BaseServiceProvider
{
    private const CONFIG_KEY = 'reliqarts-common';
    private const ASSET_DIRECTORY = __DIR__ . '/..';
    private const LOGGER_NAME = 'reliqarts-common-logger';
    private const LOG_FILENAME = self::CONFIG_KEY;

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
        $this->registerBindings();
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
    private function handleConfig()
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

    /**
     * @return self
     */
    private function registerBindings(): self
    {
        $this->app->singleton(
            Contracts\ConfigProvider::class,
            function (): Contracts\ConfigProvider {
                return new Services\ConfigProvider(
                    resolve(ConfigRepository::class),
                    $this->getConfigKey()
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
                $logFile = storage_path(sprintf('logs/%s.log', $this->getLogFilename()));
                $logger->pushHandler(new StreamHandler($logFile, Services\Logger::DEBUG));

                return $logger;
            }
        );

        return $this;
    }
}
