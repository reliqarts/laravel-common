<?php

declare(strict_types=1);

namespace ReliqArts\Tests\Unit;

use Exception;
use ReliqArts\Result;
use ReliqArts\Tests\TestCase;

/**
 * Class ResultTest.
 *
 * @coversDefaultClass \ReliqArts\Result
 *
 * @internal
 */
final class ResultTest extends TestCase
{
    private Result $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new Result();
    }

    /**
     * @throws Exception
     */
    public function testInitialState(): void
    {
        $result = $this->subject;

        self::assertTrue($result->isSuccess());
        self::assertEmpty($result->getError());
        self::assertEmpty($result->getMessages());
    }

    /**
     * @throws Exception
     */
    public function testErrorResult(): void
    {
        $error = 'My error';
        $result = $this->subject->setError($error);

        self::assertFalse($result->isSuccess());
        self::assertSame($error, $result->getError());
    }

    /**
     * @throws Exception
     */
    public function testResultWithMessages(): void
    {
        $messages = ['hi', 'hello'];
        $result = $this->subject->setMessages(...$messages);

        self::assertTrue($result->isSuccess());
        self::assertSame($messages[0], $result->getMessage());
        self::assertSame($messages, $result->getMessages());
    }

    /**
     * @throws Exception
     */
    public function testResultIsProperlySerializable(): void
    {
        $serializedResult = $this->subject->jsonSerialize();

        self::assertArrayHasKey('success', $serializedResult);
        self::assertArrayHasKey('error', $serializedResult);
        self::assertArrayHasKey('messages', $serializedResult);
        self::assertArrayHasKey('extra', $serializedResult);
    }
}
