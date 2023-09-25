<?php

declare(strict_types=1);

namespace ReliqArts\Service;

use Illuminate\Contracts\Config\Repository;
use ReliqArts\Contract\ConfigProvider as ConfigProviderContract;

readonly class ConfigProvider implements ConfigProviderContract
{
    public function __construct(protected Repository $repository, protected string $namespace)
    {
    }

    /**
     * @param  mixed  $default
     * @return mixed
     */
    public function get(?string $key, $default = null)
    {
        if (empty($key)) {
            return $this->repository->get($this->namespace, []);
        }

        return $this->repository->get(
            sprintf('%s.%s', $this->namespace, $key),
            $default
        );
    }
}
