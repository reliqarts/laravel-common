<?php

/**
 * @noinspection PhpTooManyParametersInspection
 */

declare(strict_types=1);

namespace ReliqArts\Service;

use Illuminate\Support\Collection;
use ReliqArts\Contract\DescendantsFinder as DescendantsFinderContract;

final readonly class DescendantsFinder implements DescendantsFinderContract
{
    public function findDescendantsInCollection(
        int $parentId,
        Collection $collection,
        bool $idsOnly = false,
        string $parentIdAttributeName = self::DEFAULT_PARENT_ID_ATTRIBUTE
    ): array {
        $descendants = [];

        foreach ($collection as $item) {
            if (($item->{$parentIdAttributeName} ?? null) === $parentId && $parentId !== $item->id) {
                array_push(
                    $descendants,
                    ...array_merge(
                        [$idsOnly ? $item->id : $item],
                        $this->findDescendantsInCollection($item->id, $collection, $idsOnly, $parentIdAttributeName)
                    )
                );
            }
        }

        return $descendants;
    }
}
