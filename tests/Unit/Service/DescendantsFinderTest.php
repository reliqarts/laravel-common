<?php

/**
 * @noinspection PhpTooManyParametersInspection
 */

declare(strict_types=1);

namespace ReliqArts\Tests\Unit\Service;

use Exception;
use Illuminate\Support\Collection;
use ReliqArts\Contract\DescendantsFinder as DescendantsFinderContract;
use ReliqArts\Service\DescendantsFinder;
use ReliqArts\Tests\TestCase;
use stdClass;

/**
 * @coversDefaultClass \ReliqArts\Service\DescendantsFinder
 */
final class DescendantsFinderTest extends TestCase
{
    /**
     * @covers ::findDescendantsInCollection
     *
     * @dataProvider dataProvider
     *
     * @throws Exception
     */
    public function testFindDescendants(
        int $parentId,
        Collection $collection,
        array $expectedResult,
        bool $idsOnly = false,
        string $parentIdAttributeName = DescendantsFinderContract::DEFAULT_PARENT_ID_ATTRIBUTE
    ): void {
        $subject = new DescendantsFinder();
        $result = $subject->findDescendantsInCollection($parentId, $collection, $idsOnly, $parentIdAttributeName);

        self::assertSameSize($expectedResult, $result);

        foreach ($result as $item) {
            self::assertContains($item, $expectedResult);
        }
    }

    public function dataProvider(): array
    {
        $item1 = $this->getItem(1);
        $item2 = $this->getItem(2, 1);
        $item3 = $this->getItem(3, 1);
        $item4 = $this->getItem(4);
        $item5 = $this->getItem(5, 4);
        $item6 = $this->getItem(6, 7);
        $item7 = $this->getItem(7, 5);
        $item8 = $this->getItem(8, 7);
        $item9 = $this->getItem(9, 8, 'foo');
        $item10 = $this->getItem(10, 9, 'foo');
        $item11 = $this->getItem(11, 8, 'foo');
        $item12 = $this->getItem(12, 12);
        $item13 = $this->getItem(13, 12);
        $item14 = $this->getItem(14, 14);

        $collection = collect(
            [
                $item1,
                $item2,
                $item3,
                $item4,
                $item5,
                $item6,
                $item7,
                $item8,
                $item9,
                $item10,
                $item11,
                $item12,
                $item13,
                $item14,
            ]
        );

        return [
            'single level' => [
                1,
                $collection,
                [$item2, $item3],
            ],
            'multi-level' => [
                4,
                $collection,
                [$item5, $item6, $item7, $item8],
            ],
            'IDs only' => [
                1,
                $collection,
                [2, 3],
                true,
            ],
            'different parent attribute' => [
                8,
                $collection,
                [$item9, $item10, $item11],
                false,
                'foo',
            ],
            'different parent attribute - IDs only' => [
                8,
                $collection,
                [9, 10, 11],
                true,
                'foo',
            ],
            'expecting empty' => [
                20,
                $collection,
                [],
            ],
            'recursion check - 1' => [
                12,
                $collection,
                [$item13],
            ],
            'recursion check - 2' => [
                14,
                $collection,
                [],
            ],
        ];
    }

    private function getItem(
        int $id,
        int $parentId = null,
        string $parentIdAttributeName = DescendantsFinderContract::DEFAULT_PARENT_ID_ATTRIBUTE
    ): stdClass {
        $item = new stdClass();
        $item->id = $id;
        $item->{$parentIdAttributeName} = $parentId;

        return $item;
    }
}
