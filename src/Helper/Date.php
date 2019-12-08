<?php

declare(strict_types=1);

namespace ReliqArts\Helper;

use Carbon\Carbon;

final class Date
{
    private const RELATIVITY_THRESHOLD = 7;

    /**
     * Format a Carbon date to be relative or absolute depending on number of days away.
     *
     * @param Carbon $date
     *
     * @return string
     */
    public static function relative(Carbon $date): string
    {
        if ($date->diffInDays(Carbon::now(), true) < self::RELATIVITY_THRESHOLD) {
            return $date->diffForHumans();
        }

        return $date->toFormattedDateString();
    }
}