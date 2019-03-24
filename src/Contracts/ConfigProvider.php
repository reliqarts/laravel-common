<?php

declare(strict_types=1);

namespace ReliqArts\Contracts;

interface ConfigProvider
{
    /**
     * @param null|string $key
     * @param mixed       $default
     *
     * @return mixed
     */
    public function get(?string $key, $default = null);
}
