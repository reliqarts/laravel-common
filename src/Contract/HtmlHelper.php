<?php

declare(strict_types=1);

namespace ReliqArts\Contract;

interface HtmlHelper
{
    /**
     * @return string
     */
    public function stripTags(string $html, ?string $allowedTags = null): string;
}
