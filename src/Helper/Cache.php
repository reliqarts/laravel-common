<?php

declare(strict_types=1);

namespace ReliqArts\Helper;

use Illuminate\Support\Facades\Cache as IlluminateCache;
use Carbon\Carbon;
use ReliqArts\Contract\CacheHelper;

final class Cache implements CacheHelper
{
    /**
     * @param string ...$thing
     *
     * @return string
     */
    public function getCacheKeyFor(string ...$thing): string
    {
        if (empty($thing)) {
            return self::DEFAULT_CACHE_KEY;
        }

        return strtoupper(str_replace(' ', '_', implode('.', $thing)));
    }

    /**
     * @param int $hours
     *
     * @return Carbon
     */
    public function getCacheTimeout(int $hours = 12): Carbon
    {
        return Carbon::now()->addHours($hours);
    }

    public function flushCache(): void
    {
        /* @noinspection PhpUndefinedMethodInspection */
        IlluminateCache::flush();
    }
}
