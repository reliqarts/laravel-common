<?php

namespace ReliQArts\SimpleCommons\Helpers;

use File;
use Config;
use Exception;
use Illuminate\Support\Facades\Log;

class VersionHelper
{
    /**
     * Get application build number.
     *
     * @return string
     */
    public static function getBuildNumber()
    {
        $buildFile = Config::get('simplecommons.files.build');
        try {
            $buildNumber = File::get(base_path($buildFile));
        } catch (Exception $exception) {
            $buildNumber = 'x';
            Log::warning("SC build number file ({$buildFile}) doesn't exist");
        }

        return preg_replace('/[\s\r\n]/', '', $buildNumber);
    }

    /**
     * Get application version number.
     *
     * @return string
     */
    public static function getVersionNumber()
    {
        $versionFile = Config::get('simplecommons.files.version');

        try {
            $versionNumber = File::get(base_path($versionFile));
        } catch (Exception $exception) {
            $versionNumber = 'unknown';
            Log::warning("SC version file ({$versionFile}) doesn't exist");
        }

        return preg_replace('/[\s\r\n]/', '', $versionNumber);
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
}
