<?php

declare(strict_types=1);

namespace ReliqArts\Contract;

use Carbon\Carbon;

interface CacheHelper
{
    public const CACHE_TIMEOUT_HOURS_TINY = 3;
    public const CACHE_TIMEOUT_HOURS_SHORT = 6;
    public const CACHE_TIMEOUT_HOURS_MEDIUM = 12;
    public const CACHE_TIMEOUT_HOURS_LONG = 48;
    public const CACHE_TIMEOUT_HOURS_WEEK = 24 * 7;
    public const CACHE_TIMEOUT_HOURS_MONTH = 24 * 31;
    public const CACHE_TIMEOUT_HOURS_EXTRA_LONG = self::CACHE_TIMEOUT_HOURS_MONTH;
    public const DEFAULT_CACHE_KEY = 'CACHE.KEY.DEFAULT';

    /**
     * @param string ...$thing
     *
     * @return string
     */
    public function getCacheKeyFor(string ...$thing): string;

    /**
     * @return Carbon
     */
    public function getCacheTimeout(int $hours = 12): Carbon;

    public function flushCache(): void;
}
