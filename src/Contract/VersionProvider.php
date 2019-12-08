<?php

declare(strict_types=1);

namespace ReliqArts\Contract;

interface VersionProvider
{
    /**
     * Get application build number.
     */
    public function getBuildNumber(): string;

    /**
     * Get application version number.
     */
    public function getVersionNumber(): string;

    /**
     * Get current application version.
     *
     * @param bool $includeBuildNumber
     */
    public function getVersion($includeBuildNumber = true): string;
}
