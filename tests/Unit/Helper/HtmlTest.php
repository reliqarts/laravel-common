<?php

declare(strict_types=1);

namespace ReliqArts\Tests\Unit\Helper;

use ReliqArts\Contract\HtmlHelper;
use ReliqArts\Helper\Html;
use ReliqArts\Tests\TestCase;

/**
 * Class HtmlTest.
 *
 * @coversDefaultClass \ReliqArts\Helper\Html
 *
 * @internal
 */
final class HtmlTest extends TestCase
{
    private HtmlHelper $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new Html();
    }

    /**
     * @covers ::stripTags
     * @dataProvider stripTagsDataProvider
     */
    public function testStripTags(string $html, string $expectedResult, ?string $allowedTags = null): void
    {
        self::assertSame($expectedResult, $this->subject->stripTags($html, $allowedTags));
    }

    public function stripTagsDataProvider(): array
    {
        return [
            'simple' => [
                '<p>Hello.</p><div>What is going on?</div>',
                'Hello. What is going on?',
            ],
            'with image caption' => [
                '<p>Hello.</p>
                 <figure>
                    <img src="http://img" alt="i" />
                    <figcaption>Caption goes here.</figcaption>
                 </figure>
                 <div>What is going on?</div>',
                'Hello. Caption goes here. What is going on?',
            ],
        ];
    }
}
