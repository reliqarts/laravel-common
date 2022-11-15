<?php

/**
 * @noinspection PhpTooManyParametersInspection
 */

declare(strict_types=1);

namespace ReliqArts\Helper;

use ReliqArts\Contract\ProcessHelper;
use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Process\Process as SymfonyProcess;

final class Process implements ProcessHelper
{
    /**
     * @throws LogicException
     */
    public function createProcess(
        array|string $command,
        string $cwd = null,
        array $env = null,
        $input = null,
        ?float $timeout = 60
    ): SymfonyProcess {
        return new SymfonyProcess($command, $cwd, $env, $input, $timeout);
    }

    /**
     * @throws LogicException
     */
    public function createProcessFromShellCommandline(string $command): SymfonyProcess
    {
        return SymfonyProcess::fromShellCommandline($command);
    }
}
