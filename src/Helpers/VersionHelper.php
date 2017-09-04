<?php

namespace ReliQArts\SimpleCommons\Helpers;

use File;
use Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Filesystem\FileNotFoundException;

class VersionHelper
{
    /**
     * Get application build number.
     *
     * @return string
     */
    public function getBuildNumber()
    {
        $buildFile = Config::get('simplecommons.files.build');
        try {
            $buildNumber = File::get($buildFile);
        } catch (FileNotFoundException $exception) {
            $buildNumber = 'x';
            Log::warning("SC build number file ({$buildFile}) doesn't exist");
        }

        return $buildNumber;
    }

    /**
     * Get application version number.
     *
     * @return string
     */
    public function getVersionNumber()
    {
        $versionFile = Config::get('simplecommons.files.version');
        try {
            $versionNumber = File::get($versionFile);
        } catch (FileNotFoundException $exception) {
            $versionNumber = 'unknown';
            Log::warning("SC version file ({$versionFile}) doesn't exist");
        }

        return $versionNumber;
    }

    /**
     * Get current application version.
     * 
     * @param bool $includeBuild Whether build number should be included.
     * 
     * @return string
     */
    public static function getVersion($includeBuild = true)
    {
        $version = self::getVersionNumber();
        if ($includeBuild) {
            $version = "$version.".self::getBuildNumber();
        }

        return $version;
    }

    /**
     * Get guided imageables table.
     */
    public static function getImageablesTable()
    {
        return Config::get('simplecommons.files.build', 'imageables');
    }
}
