<?php

declare(strict_types=1);

namespace ReliqArts\Helper;

use ReliqArts\Contract\HtmlHelper;

final class Html implements HtmlHelper
{
    private const DUPLICATE_SPACES_PATTERN = '/\s{2,}/';
    private const SINGLE_SPACE = ' ';
    private const TAG_START = '<';

    public function stripTags(string $html, ?string $allowedTags = null): string
    {
        $stripped = strip_tags(str_replace(self::TAG_START, self::SINGLE_SPACE . self::TAG_START, $html), $allowedTags);

        return trim(preg_replace(self::DUPLICATE_SPACES_PATTERN, self::SINGLE_SPACE, $stripped));
    }
}
