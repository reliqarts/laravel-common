<?php

declare(strict_types=1);

namespace ReliqArts\Service;

use Illuminate\Contracts\Config\Repository;
use ReliqArts\Contract\ConfigProvider as ConfigProviderContract;

class ConfigProvider implements ConfigProviderContract
{
    /**
     * @var Repository
     */
    protected Repository $repository;

    /**
     * @var string
     */
    protected string $namespace;

    /**
     * ConfigProvider constructor.
     */
    public function __construct(Repository $repository, string $namespace)
    {
        $this->repository = $repository;
        $this->namespace = $namespace;
    }

    /**
     * @param mixed $default
     *
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
