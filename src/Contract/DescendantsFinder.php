<?php

/**
 * @noinspection PhpTooManyParametersInspection
 */

declare(strict_types=1);

namespace ReliqArts\Contract;

use Illuminate\Support\Collection;

interface DescendantsFinder
{
    public const DEFAULT_PARENT_ID_ATTRIBUTE = 'parent_id';

    /**
     * Get list of descendants for an item in a collection.
     * Note: collection items must have `id` and `parent_id` properties.
     *
     * @param int        $parentId   Parent item (query item) ID.
     * @param Collection $collection Collection to search.
     * @param bool       $idsOnly    Whether to return an array of IDs instead of an array of items.
     */
    public function findDescendantsInCollection(
        int $parentId,
        Collection $collection,
        bool $idsOnly = false,
        string $parentIdAttributeName = self::DEFAULT_PARENT_ID_ATTRIBUTE
    ): array;
}
