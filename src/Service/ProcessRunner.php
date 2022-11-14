<?php

declare(strict_types=1);

namespace ReliqArts\Service;

use ReliqArts\Common\Contract\ProcessHelper;
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
    public function run(array $command, string $workingDirectory): Process
    {
        $process = $this->processHelper->createProcess($command, $workingDirectory);
        $process->mustRun();

        return $process;
    }
}
