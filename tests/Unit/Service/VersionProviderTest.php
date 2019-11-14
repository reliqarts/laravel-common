<?php

/** @noinspection PhpStrictTypeCheckingInspection */

declare(strict_types=1);

namespace ReliqArts\Tests\Unit\Service;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use ReliqArts\Contract\ConfigProvider;
use ReliqArts\Contract\Filesystem;
use ReliqArts\Contract\Logger;
use ReliqArts\Service\VersionProvider;
use ReliqArts\Tests\TestCase;

/**
 * Class VersionProviderTest.
 *
 * @coversDefaultClass \ReliqArts\Service\VersionProvider
 *
 * @internal
 */
final class VersionProviderTest extends TestCase
{
    private const ARBITRARY_FILE_PATH = 'file.ext';
    private const DEFAULT_BUILD = VersionProvider::DEFAULT_BUILD;
    private const DEFAULT_VERSION = VersionProvider::DEFAULT_VERSION;

    /**
     * @var ConfigProvider|ObjectProphecy
     */
    private $configProvider;

    /**
     * @var Filesystem|ObjectProphecy
     */
    private $filesystem;

    /**
     * @var Logger|ObjectProphecy
     */
    private $logger;

    /**
     * @var VersionProvider
     */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->configProvider = $this->prophesize(ConfigProvider::class);
        $this->filesystem = $this->prophesize(Filesystem::class);
        $this->logger = $this->prophesize(Logger::class);

        $this->configProvider->get(Argument::that(function ($key) {
            return stripos($key, 'files.') === 0;
        }))->willReturn(self::ARBITRARY_FILE_PATH);

        $this->subject = new VersionProvider(
            $this->configProvider->reveal(),
            $this->filesystem->reveal(),
            $this->logger->reveal()
        );
    }

    /**
     * @covers ::__construct
     * @covers ::cleanText
     * @covers ::getBuildNumber
     *
     * @throws FileNotFoundException
     */
    public function testGetBuildNumber(): void
    {
        $buildFile = self::ARBITRARY_FILE_PATH;
        $buildNumber = "3\n921";
        $expectedBuildNumber = '3921';

        $this->filesystem->get(base_path($buildFile))
            ->shouldBeCalledTimes(1)
            ->willReturn($buildNumber);

        $result = $this->subject->getBuildNumber();

        $this->assertIsString($result);
        $this->assertSame($expectedBuildNumber, $result);
    }

    /**
     * @covers ::__construct
     * @covers ::cleanText
     * @covers ::getBuildNumber
     * @covers ::logWarning
     *
     * @throws FileNotFoundException
     */
    public function testGetBuildNumberWhenBuildNumberFileDoesNotExist(): void
    {
        $buildFile = self::ARBITRARY_FILE_PATH;
        $expectedBuildNumber = self::DEFAULT_BUILD;

        $this->filesystem->get(base_path($buildFile))
            ->shouldBeCalledTimes(2)
            ->willThrow(FileNotFoundException::class);
        $this->logger->warning(
            Argument::that(function ($message) use ($buildFile) {
                $expectedMessage = sprintf('build number file (%s) does not exist', $buildFile);

                return stripos($message, $expectedMessage) === 0;
            }),
            ['in' => VersionProvider::class]
        )->shouldBeCalledTimes(1);

        $result1 = $this->subject->getBuildNumber();
        $result2 = $this->subject->getBuildNumber();

        $this->assertIsString($result1);
        $this->assertIsString($result2);
        $this->assertSame($expectedBuildNumber, $result1);
        $this->assertSame($expectedBuildNumber, $result2);
    }

    /**
     * @covers ::__construct
     * @covers ::cleanText
     * @covers ::getVersionNumber
     *
     * @throws FileNotFoundException
     */
    public function testGetVersionNumber(): void
    {
        $versionFile = self::ARBITRARY_FILE_PATH;
        $versionNumber = "1.\r\n3";
        $expectedVersionNumber = '1.3';

        $this->filesystem->get(base_path($versionFile))
            ->shouldBeCalledTimes(1)
            ->willReturn($versionNumber);

        $result = $this->subject->getVersionNumber();

        $this->assertIsString($result);
        $this->assertSame($expectedVersionNumber, $result);
    }

    /**
     * @covers ::__construct
     * @covers ::cleanText
     * @covers ::getVersionNumber
     * @covers ::logWarning
     *
     * @throws FileNotFoundException
     */
    public function testGetVersionNumberWhenVersionNumberFileDoesNotExist(): void
    {
        $versionFile = self::ARBITRARY_FILE_PATH;
        $expectedVersion = self::DEFAULT_VERSION;

        $this->filesystem->get(base_path($versionFile))
            ->shouldBeCalledTimes(2)
            ->willThrow(FileNotFoundException::class);
        $this->logger->warning(
            Argument::that(function ($message) use ($versionFile) {
                $expectedMessage = sprintf('version file (%s) does not exist', $versionFile);

                return stripos($message, $expectedMessage) === 0;
            }),
            ['in' => VersionProvider::class]
        )->shouldBeCalledTimes(1);

        $result1 = $this->subject->getVersionNumber();
        $result2 = $this->subject->getVersionNumber();

        $this->assertIsString($result1);
        $this->assertIsString($result2);
        $this->assertSame($expectedVersion, $result1);
        $this->assertSame($expectedVersion, $result2);
    }

    /**
     * @covers ::__construct
     * @covers ::cleanText
     * @covers ::getBuildNumber
     * @covers ::getVersion
     * @covers ::getVersionNumber
     */
    public function testGetVersion(): void
    {
        $versionFile = sprintf('%s.version', self::ARBITRARY_FILE_PATH);
        $buildFile = sprintf('%s.build', self::ARBITRARY_FILE_PATH);
        $versionNumber = "1.\r\n3";
        $buildNumber = "1\n232";
        $expectedVersionNumber = '1.3';
        $expectedBuildNumber = '1232';
        $expectedVersionNumberWithBuild = sprintf('%s.%s', $expectedVersionNumber, $expectedBuildNumber);

        $this->configProvider->get('files.version')->willReturn($versionFile);
        $this->configProvider->get('files.build')->willReturn($buildFile);
        $this->filesystem->get(base_path($versionFile))
            ->shouldBeCalledTimes(2)
            ->willReturn($versionNumber);
        $this->filesystem->get(base_path($buildFile))
            ->shouldBeCalledTimes(1)
            ->willReturn($buildNumber);

        $result = $this->subject->getVersion(false);
        $resultWithBuildNumber = $this->subject->getVersion();

        $this->assertIsString($result);
        $this->assertIsString($resultWithBuildNumber);
        $this->assertSame($expectedVersionNumber, $result);
        $this->assertSame($expectedVersionNumberWithBuild, $resultWithBuildNumber);
    }
}
