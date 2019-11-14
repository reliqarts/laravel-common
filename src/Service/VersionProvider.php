<?php

declare(strict_types=1);

namespace ReliqArts\Service;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use ReliqArts\Contract\ConfigProvider;
use ReliqArts\Contract\Filesystem;
use ReliqArts\Contract\Logger;
use ReliqArts\Contract\VersionProvider as VersionProviderContract;

class VersionProvider implements VersionProviderContract
{
    public const DEFAULT_BUILD = '0x1';
    public const DEFAULT_VERSION = 'dev';

    /**
     * @var ConfigProvider
     */
    protected $configProvider;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var string[]
     */
    private $warningsLogged;

    /**
     * VersionProvider constructor.
     *
     * @param ConfigProvider $configProvider
     * @param Filesystem     $filesystem
     * @param Logger         $logger
     */
    public function __construct(ConfigProvider $configProvider, Filesystem $filesystem, Logger $logger)
    {
        $this->warningsLogged = [];
        $this->configProvider = $configProvider;
        $this->filesystem = $filesystem;
        $this->logger = $logger;
    }

    /**
     * Get application build number.
     *
     * @return string
     */
    public function getBuildNumber(): string
    {
        $buildFile = $this->configProvider->get('files.build');

        try {
            $buildNumber = $this->filesystem->get(base_path($buildFile));
        } catch (FileNotFoundException $exception) {
            $buildNumber = self::DEFAULT_BUILD;
            $message = sprintf(
                'Build number file (%s) does not exist. Falling back to default build: %s',
                $buildFile,
                $buildNumber
            );

            $this->logWarning($message);
        }

        return $this->cleanText($buildNumber);
    }

    /**
     * Get application version number.
     *
     * @return string
     */
    public function getVersionNumber(): string
    {
        $versionFile = $this->configProvider->get('files.version');

        try {
            $version = $this->filesystem->get(base_path($versionFile));
        } catch (FileNotFoundException $exception) {
            $version = self::DEFAULT_VERSION;
            $message = sprintf(
                'Version file (%s) does not exist. Falling back to default version: %s',
                $versionFile,
                $version
            );

            $this->logWarning($message);
        }

        return $this->cleanText($version);
    }

    /**
     * Get current application version.
     *
     * @param bool $includeBuildNumber
     *
     * @return string
     */
    public function getVersion($includeBuildNumber = true): string
    {
        $version = $this->getVersionNumber();

        if ($includeBuildNumber) {
            $version = sprintf('%s.%s', $version, $this->getBuildNumber());
        }

        return $version;
    }

    /**
     * @param string $text
     *
     * @return string
     */
    private function cleanText(string $text): string
    {
        $pattern = '/[\s\r\n]/';

        return preg_replace($pattern, '', $text);
    }

    /**
     * @param string $message
     */
    private function logWarning(string $message): void
    {
        if (in_array($message, $this->warningsLogged, true)) {
            return;
        }

        $this->logger->warning($message, ['in' => self::class]);
        $this->warningsLogged[] = $message;
    }
}
