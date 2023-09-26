<?php

declare(strict_types=1);

namespace ReliqArts;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use ReliqArts\Console\Command\GenerateSitemap;
use ReliqArts\Console\Command\NewRelease;
use ReliqArts\Contract\CacheHelper;
use ReliqArts\Contract\ConfigProvider as ConfigProviderContract;
use ReliqArts\Contract\DescendantsFinder as DescendantsFinderContract;
use ReliqArts\Contract\Filesystem as FilesystemContract;
use ReliqArts\Contract\HtmlHelper;
use ReliqArts\Contract\Logger as LoggerContract;
use ReliqArts\Contract\LoggerFactory as LoggerFactoryContract;
use ReliqArts\Contract\ProcessHelper;
use ReliqArts\Contract\ProcessRunner as ProcessRunnerContract;
use ReliqArts\Contract\VersionProvider as VersionProviderContract;
use ReliqArts\Factory\LoggerFactory;
use ReliqArts\Helper\Cache;
use ReliqArts\Helper\Html;
use ReliqArts\Helper\Process;
use ReliqArts\Service\ConfigProvider;
use ReliqArts\Service\DescendantsFinder;
use ReliqArts\Service\Filesystem;
use ReliqArts\Service\ProcessRunner;
use ReliqArts\Service\VersionProvider;

/**
 * @codeCoverageIgnore
 */
class ServiceProvider extends BaseServiceProvider
{
    protected const CONFIG_KEY = 'reliqarts-common';

    protected const ASSET_DIRECTORY = __DIR__.'/..';

    protected const LOGGER_NAME = 'reliqarts-common-logger';

    protected const LOG_FILE_BASENAME = self::CONFIG_KEY;

    /**
     * List of commands.
     */
    protected array $commands = [
        GenerateSitemap::class,
        NewRelease::class,
    ];

    /**
     * Bootstrap the package.
     */
    public function boot(): void
    {
        $this->handleConfig();
        $this->handleCommands();
        $this->handleViews();
    }

    /**
     * Register package services.
     */
    public function register(): void
    {
        $this->app->singleton(FilesystemContract::class, Filesystem::class);
        $this->app->singleton(VersionProviderContract::class, VersionProvider::class);
        $this->app->singleton(CacheHelper::class, Cache::class);
        $this->app->singleton(HtmlHelper::class, Html::class);
        $this->app->singleton(DescendantsFinderContract::class, DescendantsFinder::class);
        $this->app->singleton(ProcessHelper::class, Process::class);
        $this->app->singleton(ProcessRunnerContract::class, ProcessRunner::class);
        $this->app->singleton(LoggerFactoryContract::class, LoggerFactory::class);

        $this->app->singleton(
            ConfigProviderContract::class,
            static fn (): ConfigProviderContract => new ConfigProvider(
                resolve(ConfigRepository::class),
                $this->getConfigKey()
            )
        );

        $this->app->singleton(
            LoggerContract::class,
            fn (LoggerFactory $loggerFactory): LoggerContract => $loggerFactory->create(
                $this->getLoggerName(),
                $this->getLogFileBasename()
            )
        );
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            ConfigProviderContract::class,
            FilesystemContract::class,
            VersionProviderContract::class,
            LoggerContract::class,
        ];
    }

    protected function getAssetDirectory(): string
    {
        return static::ASSET_DIRECTORY;
    }

    protected function getConfigKey(): string
    {
        return static::CONFIG_KEY;
    }

    protected function getLogFileBasename(): string
    {
        return static::LOG_FILE_BASENAME;
    }

    protected function getLoggerName(): string
    {
        return static::LOGGER_NAME;
    }

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

    private function handleCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }
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
