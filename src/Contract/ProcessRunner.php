<?php

declare(strict_types=1);

namespace ReliqArts\Contract;

use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

interface ProcessRunner
{
    /**
     * @throws LogicException|ProcessFailedException
     */
    public function run(array $command, string $workingDirectory = null): Process;

    /**
     * @throws LogicException|ProcessFailedException
     */
    public function runShellCommand(string $command, array $env = []): Process;
}
