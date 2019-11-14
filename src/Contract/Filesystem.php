<?php

declare(strict_types=1);

namespace ReliqArts\Contract;

/**
 * Interface Filesystem.
 *
 * @mixin \Illuminate\Filesystem\Filesystem
 */
interface Filesystem
{
    /**
     * Get the contents of a file.
     *
     * @param string $path
     * @param bool   $lock
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @return string
     */
    public function get($path, $lock = false);

    /**
     * @param string $directory
     * @param bool   $preserve
     *
     * @return bool
     */
    public function deleteDirectory($directory, $preserve = false);
}
