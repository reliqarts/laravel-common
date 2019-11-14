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
    protected $repository;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * ConfigProvider constructor.
     *
     * @param Repository $repository
     * @param string     $namespace
     */
    public function __construct(Repository $repository, string $namespace)
    {
        $this->repository = $repository;
        $this->namespace = $namespace;
    }

    /**
     * @param null|string $key
     * @param mixed       $default
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
