<?php

declare(strict_types=1);

namespace ReliqArts\Tests\Integration\Service;

use ReliqArts\Contract\ConfigProvider as ConfigProviderContract;
use ReliqArts\Tests\Integration\TestCase;
use Throwable;

final class ConfigProviderTest extends TestCase
{
    private ConfigProviderContract $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = resolve(ConfigProviderContract::class);
    }

    /**
     * @throws Throwable
     */
    public function testGetConfig(): void
    {
        $config = $this->subject->get(null);

        self::assertIsArray($config);
        self::assertFalse($this->subject->get('debug'));
        self::assertSame($config['debug'], $this->subject->get('debug'));
        self::assertSame('build.number', $this->subject->get('files.build'));
        self::assertSame('version.number', $this->subject->get('files.version'));
    }
}
