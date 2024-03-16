<?php

declare(strict_types=1);

namespace ReliqArts\Tests\Unit\Service;

use Exception;
use Illuminate\Contracts\Config\Repository;
use PHPUnit\Framework\Attributes\CoversClass;
use Prophecy\Exception\Doubler\DoubleException;
use Prophecy\Exception\Doubler\InterfaceNotFoundException;
use Prophecy\Exception\Prophecy\ObjectProphecyException;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use ReliqArts\Service\ConfigProvider;
use ReliqArts\Tests\TestCase;

#[CoversClass(ConfigProvider::class)]
final class ConfigProviderTest extends TestCase
{
    use ProphecyTrait;

    private string $namespace;

    private ObjectProphecy|Repository $repository;

    private ConfigProvider $subject;

    /**
     * @throws DoubleException
     * @throws InterfaceNotFoundException
     * @throws ObjectProphecyException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->namespace = 'test';
        $this->repository = $this->prophesize(Repository::class);
        $this->subject = new ConfigProvider($this->repository->reveal(), $this->namespace);
    }

    /**
     * @throws Exception
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

        self::assertNotEmpty($result);
        self::assertSame($expectedValue, $result);
    }

    /**
     * @throws Exception
     */
    public function testGetWhenKeyIsEmpty(): void
    {
        $expectedValue = ['some-key', 'some-value'];

        $this->repository->get($this->namespace, [])->shouldBeCalledTimes(2)->willReturn($expectedValue);

        $result1 = $this->subject->get(null);
        $result2 = $this->subject->get('');

        self::assertNotEmpty($result1);
        self::assertNotEmpty($result2);
        self::assertSame($expectedValue, $result1);
        self::assertSame($expectedValue, $result2);
    }
}
