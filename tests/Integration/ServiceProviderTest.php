<?php

declare(strict_types=1);

namespace ReliqArts\Tests\Integration;

use Exception;
use PHPUnit\Framework\Attributes\DataProvider;
use ReliqArts\Contract\ConfigProvider;
use ReliqArts\Contract\Filesystem;
use ReliqArts\Contract\Logger;
use ReliqArts\Contract\VersionProvider;

final class ServiceProviderTest extends TestCase
{
    /**
     * @throws Exception
     */
    #[DataProvider('providedInterfaceDataProvider')]
    public function testProvides(string $interface): void
    {
        self::assertInstanceOf($interface, resolve($interface));
    }

    public static function providedInterfaceDataProvider(): array
    {
        return [
            ConfigProvider::class => [ConfigProvider::class],
            Filesystem::class => [Filesystem::class],
            VersionProvider::class => [VersionProvider::class],
            Logger::class => [Logger::class],
        ];
    }
}
