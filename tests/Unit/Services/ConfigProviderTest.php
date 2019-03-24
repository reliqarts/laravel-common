<?php

declare(strict_types=1);

namespace ReliqArts\Tests\Unit\Services;

use Illuminate\Contracts\Config\Repository;
use Prophecy\Prophecy\ObjectProphecy;
use ReliqArts\Services\ConfigProvider;
use ReliqArts\Tests\TestCase;

/**
 * Class ConfigProviderTest.
 *
 * @coversDefaultClass \ReliqArts\Services\ConfigProvider
 *
 * @internal
 */
final class ConfigProviderTest extends TestCase
{
    /**
     * @var string
     */
    private $namespace;

    /**
     * @var ObjectProphecy|Repository
     */
    private $repository;

    /**
     * @var ConfigProvider
     */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->namespace = 'test';
        $this->repository = $this->prophesize(Repository::class);
        $this->subject = new ConfigProvider($this->repository->reveal(), $this->namespace);
    }

    /**
     * @covers ::__construct
     * @covers ::get
     */
    public function testGet(): void
    {
        $key = 'foo.bar';
        $default = 'default-value';
        $expectedValue = 'some-value';

        $this->repository->get(
            sprintf('%s.%s', $this->namespace, $key),
            $default
        )->shouldBeCalledTimes(1)->willReturn($expectedValue);

        $result = $this->subject->get($key, $default);

        $this->assertNotEmpty($result);
        $this->assertSame($expectedValue, $result);
    }

    /**
     * @covers ::__construct
     * @covers ::get
     */
    public function testGetWhenKeyIsEmpty(): void
    {
        $expectedValue = ['some-key', 'some-value'];

        $this->repository->get($this->namespace, [])->shouldBeCalledTimes(2)->willReturn($expectedValue);

        $result1 = $this->subject->get(null);
        $result2 = $this->subject->get('');

        $this->assertNotEmpty($result1);
        $this->assertNotEmpty($result2);
        $this->assertSame($expectedValue, $result1);
        $this->assertSame($expectedValue, $result2);
    }
}
