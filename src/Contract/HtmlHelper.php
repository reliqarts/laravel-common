<?php

declare(strict_types=1);

namespace ReliqArts\Contract;

interface HtmlHelper
{
    /**
     * Strip tags elegantly; leaving adequately spaced words.
     */
    public function stripTags(string $html, ?string $allowedTags = null): string;
}
