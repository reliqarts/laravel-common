<?php

/**
 * @noinspection PhpTooManyParametersInspection
 */

declare(strict_types=1);

namespace ReliqArts\Tests\Unit\Service;

use Exception;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use ReliqArts\Contract\DescendantsFinder as DescendantsFinderContract;
use ReliqArts\Service\DescendantsFinder;
use ReliqArts\Tests\TestCase;
use stdClass;

#[CoversClass(DescendantsFinder::class)]
final class DescendantsFinderTest extends TestCase
{
    /**
     * @throws Exception
     */
    #[DataProvider('dataProvider')]
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

    public static function dataProvider(): array
    {
        $item1 = self::getItem(1);
        $item2 = self::getItem(2, 1);
        $item3 = self::getItem(3, 1);
        $item4 = self::getItem(4);
        $item5 = self::getItem(5, 4);
        $item6 = self::getItem(6, 7);
        $item7 = self::getItem(7, 5);
        $item8 = self::getItem(8, 7);
        $item9 = self::getItem(9, 8, 'foo');
        $item10 = self::getItem(10, 9, 'foo');
        $item11 = self::getItem(11, 8, 'foo');
        $item12 = self::getItem(12, 12);
        $item13 = self::getItem(13, 12);
        $item14 = self::getItem(14, 14);

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

    private static function getItem(
        int $id,
        ?int $parentId = null,
        string $parentIdAttributeName = DescendantsFinderContract::DEFAULT_PARENT_ID_ATTRIBUTE
    ): stdClass {
        $item = new stdClass();
        $item->id = $id;
        $item->{$parentIdAttributeName} = $parentId;

        return $item;
    }
}
