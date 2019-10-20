<?php

declare(strict_types=1);

namespace ReliqArts\Tests\Unit;

use ReliqArts\Result;
use ReliqArts\Tests\TestCase;

/**
 * Class ResultTest
 *
 * @coversDefaultClass \ReliqArts\Result
 */
final class ResultTest extends TestCase
{
    /**
     * @var Result
     */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new Result();
    }

    public function testInitialState(): void
    {
        $result = $this->subject;

        $this->assertTrue($result->isSuccess());
        $this->assertEmpty($result->getError());
        $this->assertEmpty($result->getMessages());
    }

    public function testErrorResult(): void
    {
        $error = 'My error';
        $result = $this->subject->setError($error);

        $this->assertFalse($result->isSuccess());
        $this->assertSame($error, $result->getError());
    }

    public function testResultWithMessages(): void
    {
        $messages = ['hi', 'hello'];
        $result = $this->subject->setMessages(...$messages);

        $this->assertTrue($result->isSuccess());
        $this->assertSame($messages[0], $result->getMessage());
        $this->assertSame($messages, $result->getMessages());
    }

    public function testResultIsProperlySerializable(): void
    {
        $serializedResult = $this->subject->jsonSerialize();

        $this->assertArrayHasKey('success', $serializedResult);
        $this->assertArrayHasKey('error', $serializedResult);
        $this->assertArrayHasKey('messages', $serializedResult);
        $this->assertArrayHasKey('data', $serializedResult);
    }
}
