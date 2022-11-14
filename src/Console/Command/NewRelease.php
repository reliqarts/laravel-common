<?php

declare(strict_types=1);

namespace ReliqArts\Console\Command;

use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ExceptionInterface;
use Symfony\Component\Process\Process;

final class NewRelease extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'common:release:new {version}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Release a new version';

    /**
     * Execute the console command.
     *
     * @return int
     * @throws ExceptionInterface
     */
    public function handle(): int
    {
        $version = $this->argument('version');

        $this->line(sprintf('Attempting to release version %s.', $version));

        if (!$this->isGitFlowAvailable()) {
            $this->error('Git flow is not available.');
            $this->comment('Please install and init Git Flow before trying again: https://github.com/nvie/gitflow');

            return 1;
        }

        return 0;
    }

    /**
     * @throws ExceptionInterface
     */
    private function isGitFlowAvailable(): bool
    {
        $process = new Process(['git', 'flow', 'version']);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->comment('Failed to get Git Flow version.');
            $this->line(
                sprintf(
                    "%s - %s \n%s",
                    $process->getExitCode(),
                    $process->getExitCodeText(),
                    $process->getOutput()
                )
            );

            return false;
        }

        $this->comment(sprintf('Git Flow version: %s', $process->getOutput()));

        return true;
    }
}
