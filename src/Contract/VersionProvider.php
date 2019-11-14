<?php

declare(strict_types=1);

namespace ReliqArts\Contract;

interface VersionProvider
{
    /**
     * Get application build number.
     *
     * @return string
     */
    public function getBuildNumber(): string;

    /**
     * Get application version number.
     *
     * @return string
     */
    public function getVersionNumber(): string;

    /**
     * Get current application version.
     *
     * @param bool $includeBuildNumber
     *
     * @return string
     */
    public function getVersion($includeBuildNumber = true): string;
}
