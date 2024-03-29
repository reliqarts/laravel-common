<?php
/**
 * @noinspection PhpStrictTypeCheckingInspection
 * @noinspection PhpVoidFunctionResultUsedInspection
 */

declare(strict_types=1);

namespace ReliqArts\Tests\Unit\Service;

use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use PHPUnit\Framework\Attributes\CoversClass;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use ReliqArts\Contract\ConfigProvider;
use ReliqArts\Contract\Filesystem;
use ReliqArts\Contract\Logger;
use ReliqArts\Service\VersionProvider;
use ReliqArts\Tests\TestCase;

/**
 * @internal
 */
#[CoversClass(VersionProvider::class)]
final class VersionProviderTest extends TestCase
{
    use ProphecyTrait;

    private const ARBITRARY_FILE_PATH = 'file.ext';

    private const DEFAULT_BUILD = VersionProvider::DEFAULT_BUILD;

    private const DEFAULT_VERSION = VersionProvider::DEFAULT_VERSION;

    private ObjectProphecy|ConfigProvider $configProvider;

    private ObjectProphecy|Filesystem $filesystem;

    private ObjectProphecy|Logger $logger;

    private VersionProvider $subject;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->configProvider = $this->prophesize(ConfigProvider::class);
        $this->filesystem = $this->prophesize(Filesystem::class);
        $this->logger = $this->prophesize(Logger::class);

        $this->configProvider->get(
            Argument::that(
                static function ($key) {
                    return stripos($key, 'files.') === 0;
                }
            )
        )->willReturn(self::ARBITRARY_FILE_PATH);

        $this->subject = new VersionProvider(
            $this->configProvider->reveal(),
            $this->filesystem->reveal(),
            $this->logger->reveal()
        );
    }

    /**
     * @throws Exception
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

        self::assertIsString($result);
        self::assertSame($expectedBuildNumber, $result);
    }

    /**
     * @throws Exception
     */
    public function testGetBuildNumberWhenBuildNumberFileDoesNotExist(): void
    {
        $buildFile = self::ARBITRARY_FILE_PATH;
        $expectedBuildNumber = self::DEFAULT_BUILD;

        $this->filesystem->get(base_path($buildFile))
            ->shouldBeCalledTimes(2)
            ->willThrow(FileNotFoundException::class);
        $this->logger->warning(
            Argument::that(
                static function ($message) use ($buildFile) {
                    $expectedMessage = sprintf('build number file (%s) does not exist', $buildFile);

                    return stripos($message, $expectedMessage) === 0;
                }
            ),
            ['in' => VersionProvider::class, 'exception' => '']
        )->shouldBeCalledTimes(1);

        $result1 = $this->subject->getBuildNumber();
        $result2 = $this->subject->getBuildNumber();

        self::assertIsString($result1);
        self::assertIsString($result2);
        self::assertSame($expectedBuildNumber, $result1);
        self::assertSame($expectedBuildNumber, $result2);
    }

    /**
     * @throws Exception
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

        self::assertIsString($result);
        self::assertSame($expectedVersionNumber, $result);
    }

    /**
     * @throws Exception
     */
    public function testGetVersionNumberWhenVersionNumberFileDoesNotExist(): void
    {
        $versionFile = self::ARBITRARY_FILE_PATH;
        $expectedVersion = self::DEFAULT_VERSION;

        $this->filesystem->get(base_path($versionFile))
            ->shouldBeCalledTimes(2)
            ->willThrow(FileNotFoundException::class);
        $this->logger->warning(
            Argument::that(
                static function ($message) use ($versionFile) {
                    $expectedMessage = sprintf('version file (%s) does not exist', $versionFile);

                    return stripos($message, $expectedMessage) === 0;
                }
            ),
            ['in' => VersionProvider::class, 'exception' => '']
        )->shouldBeCalledTimes(1);

        $result1 = $this->subject->getVersionNumber();
        $result2 = $this->subject->getVersionNumber();

        self::assertIsString($result1);
        self::assertIsString($result2);
        self::assertSame($expectedVersion, $result1);
        self::assertSame($expectedVersion, $result2);
    }

    /**
     * @throws Exception
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

        self::assertIsString($result);
        self::assertIsString($resultWithBuildNumber);
        self::assertSame($expectedVersionNumber, $result);
        self::assertSame($expectedVersionNumberWithBuild, $resultWithBuildNumber);
    }
}
