<?php

declare(strict_types=1);

namespace ReliqArts\Service;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use ReliqArts\Contract\ConfigProvider;
use ReliqArts\Contract\Filesystem;
use ReliqArts\Contract\Logger;
use ReliqArts\Contract\VersionProvider as VersionProviderContract;
use Throwable;

class VersionProvider implements VersionProviderContract
{
    public const DEFAULT_BUILD = '0x1';

    public const DEFAULT_VERSION = 'dev';

    /**
     * VersionProvider constructor.
     */
    public function __construct(
        protected readonly ConfigProvider $configProvider,
        protected readonly Filesystem $filesystem,
        protected readonly Logger $logger,
        private array $warningsLogged = []
    ) {
    }

    /**
     * Get application build number.
     */
    public function getBuildNumber(string $filename = null): string
    {
        $buildFile = $filename ?? $this->configProvider->get('files.build');

        try {
            $buildNumber = $this->filesystem->get(base_path($buildFile));
        } catch (FileNotFoundException $exception) {
            $buildNumber = self::DEFAULT_BUILD;
            $message = sprintf(
                'Build number file (%s) does not exist. Falling back to default build: %s',
                $buildFile,
                $buildNumber
            );

            $this->logWarning($exception, $message);
        }

        return $this->cleanText($buildNumber);
    }

    /**
     * Get application version number.
     */
    public function getVersionNumber(string $filename = null): string
    {
        $versionFile = $filename ?? $this->configProvider->get('files.version');

        try {
            $version = $this->filesystem->get(base_path($versionFile));
        } catch (FileNotFoundException $exception) {
            $version = self::DEFAULT_VERSION;
            $message = sprintf(
                'Version file (%s) does not exist. Falling back to default version: %s',
                $versionFile,
                $version
            );

            $this->logWarning($exception, $message);
        }

        return $this->cleanText($version);
    }

    /**
     * Get current application version.
     */
    public function getVersion(
        bool $includeBuildNumber = true,
        string $versionFilename = null,
        string $buildFilename = null
    ): string {
        $version = $this->getVersionNumber($versionFilename);

        if ($includeBuildNumber) {
            $version = sprintf('%s.%s', $version, $this->getBuildNumber($buildFilename));
        }

        return $version;
    }

    private function cleanText(string $text): string
    {
        $pattern = '/[\s\r\n]/';

        return preg_replace($pattern, '', $text);
    }

    private function logWarning(Throwable $exception, string $message): void
    {
        if (in_array($message, $this->warningsLogged, true)) {
            return;
        }

        $this->logger->warning($message, ['in' => self::class, 'exception' => $exception->getMessage()]);
        $this->warningsLogged[] = $message;
    }
}
