<?php

declare(strict_types=1);

namespace ReliqArts\Service;

use ReliqArts\Contract\ProcessHelper;
use ReliqArts\Contract\ProcessRunner as ProcessRunnerContract;
use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ProcessRunner implements ProcessRunnerContract
{
    private ProcessHelper $processHelper;

    public function __construct(ProcessHelper $processHelper)
    {
        $this->processHelper = $processHelper;
    }

    /**
     * @throws LogicException|ProcessFailedException
     */
    public function run(array $command, string $workingDirectory = null): Process
    {
        $process = $this->processHelper->createProcess($command, $workingDirectory);
        $process->mustRun();

        return $process;
    }

    /**
     * @throws LogicException|ProcessFailedException
     */
    public function runShellCommand(string $command, array $env = []): Process
    {
        $process = $this->processHelper->createProcessFromShellCommandline($command);
        $process->mustRun(null, $env);

        return $process;
    }
}
