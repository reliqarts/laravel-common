<?php

declare(strict_types=1);

namespace ReliqArts\Helper;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache as IlluminateCache;
use ReliqArts\Contract\CacheHelper;

final class Cache implements CacheHelper
{
    /**
     * @param string ...$thing
     */
    public function getCacheKeyFor(string ...$thing): string
    {
        if (empty($thing)) {
            return self::DEFAULT_CACHE_KEY;
        }

        return strtoupper(str_replace(' ', '_', implode('.', $thing)));
    }

    public function getCacheTimeout(int $hours = 12): Carbon
    {
        return Carbon::now()->addHours($hours);
    }

    public function flushCache(): void
    {
        // @noinspection PhpUndefinedMethodInspection
        IlluminateCache::flush();
    }
}
