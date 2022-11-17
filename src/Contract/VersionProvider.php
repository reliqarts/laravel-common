<?php

declare(strict_types=1);

namespace ReliqArts\Contract;

interface VersionProvider
{
    /**
     * Get application build number.
     */
    public function getBuildNumber(?string $filename = null): string;

    /**
     * Get application version number.
     */
    public function getVersionNumber(?string $filename = null): string;

    /**
     * Get current application version.
     */
    public function getVersion(
        bool $includeBuildNumber = true,
        ?string $versionFilename = null,
        ?string $buildFilename = null
    ): string;
}
