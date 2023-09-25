<?php

declare(strict_types=1);

namespace ReliqArts;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger as MonologLogger;
use ReliqArts\Console\Command\GenerateSitemap;
use ReliqArts\Console\Command\NewRelease;
use ReliqArts\Contract\CacheHelper;
use ReliqArts\Contract\ConfigProvider as ConfigProviderContract;
use ReliqArts\Contract\DescendantsFinder as DescendantsFinderContract;
use ReliqArts\Contract\Filesystem as FilesystemContract;
use ReliqArts\Contract\HtmlHelper;
use ReliqArts\Contract\Logger as LoggerContract;
use ReliqArts\Contract\ProcessHelper;
use ReliqArts\Contract\ProcessRunner as ProcessRunnerContract;
use ReliqArts\Contract\VersionProvider as VersionProviderContract;
use ReliqArts\Helper\Cache;
use ReliqArts\Helper\Html;
use ReliqArts\Helper\Process;
use ReliqArts\Http\Middleware\NonWWW;
use ReliqArts\Service\ConfigProvider;
use ReliqArts\Service\DescendantsFinder;
use ReliqArts\Service\Filesystem;
use ReliqArts\Service\Logger;
use ReliqArts\Service\ProcessRunner;
use ReliqArts\Service\VersionProvider;

class ServiceProvider extends BaseServiceProvider
{
    protected const CONFIG_KEY = 'reliqarts-common';

    protected const ASSET_DIRECTORY = __DIR__.'/..';

    protected const LOGGER_NAME = 'reliqarts-common-logger';

    protected const LOG_FILENAME = self::CONFIG_KEY;

    /**
     * List of commands.
     */
    protected array $commands = [
        GenerateSitemap::class,
        NewRelease::class,
    ];

    /**
     * Bootstrap the application events.
     */
    public function boot(): void
    {
        $this->handleConfig();
        $this->handleCommands();
        $this->handleViews();
        $this->handleMiddleware();
    }

    /**
     * Register the service provider.
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

        $this->app->singleton(
            ConfigProviderContract::class,
            static fn (): ConfigProviderContract => new ConfigProvider(
                resolve(ConfigRepository::class),
                self::CONFIG_KEY
            )
        );

        $this->app->singleton(
            LoggerContract::class,
            function (): LoggerContract {
                $logFile = storage_path(sprintf('logs/%s.log', self::LOG_FILENAME));
                $internalLogger = new MonologLogger($this->getLoggerName());
                $internalLogger->pushHandler(new StreamHandler($logFile, Level::Debug));

                return new Logger($internalLogger);
            }
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

    protected function getLogFilename(): string
    {
        return static::LOG_FILENAME;
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

    private function handleMiddleware(): void
    {
        $router = $this->app['router'];

        // Register middleware...
        $router->middleware('nonWWW', NonWWW::class);
    }
}
