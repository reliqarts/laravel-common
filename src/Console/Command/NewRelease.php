<?php

declare(strict_types=1);

namespace ReliqArts\Console\Command;

use Illuminate\Console\Command;
use ReliqArts\Contract\ProcessRunner;
use Symfony\Component\Process\Exception\ExceptionInterface;

final class NewRelease extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'common:release:new 
                            {version : Version number/name e.g. v1.0, v2.0-beta} 
                            {versionFile? : (optional) File to store version number in e.g. version.file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Release a new version';

    private ProcessRunner $processRunner;

    /**
     * @param ProcessRunner $processRunner
     */
    public function __construct(ProcessRunner $processRunner)
    {
        parent::__construct();

        $this->processRunner = $processRunner;
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws ExceptionInterface
     */
    public function handle(): int
    {
        $version = $this->argument('version');
        $versionFile = $this->argument('versionFile') ?? '';

        if (!$this->isGitFlowAvailable()) {
            $this->error('Git flow is not available.');
            $this->comment('Please install and init Git Flow before trying again: https://github.com/nvie/gitflow');

            return 1;
        }
        if ('develop' !== ($this->getCurrentBranch() ?? '')) {
            $this->error('Command must be ran from the `develop` branch.');

            return 1;
        }

        $this->line(sprintf('Releasing version %s...', $version));

        if (!empty($versionFile)) {
            $this->comment("output file: \"$versionFile\"");
            $this->line("---\n");
        }

        if (!$this->releaseVersion($version, $versionFile)) {
            $this->error('Version release failed.');

            return 1;
        }

        $this->info(
            "Successfully released version $version!"
            . empty($versionFile) ? '' : " Written to \"$versionFile\"."
        );

        return 0;
    }

    /**
     * @throws ExceptionInterface
     */
    private function releaseVersion(string $version, string $versionFile = ''): bool
    {
        $commandText = 'git tag -a ${VERSION} -m "version ${VERSION}"'
            . ' && git checkout main'
            . ' && git merge ${DEVELOP_BRANCH} --no-edit --allow-unrelated-histories'
            . (empty($versionFile) ? '' : ' && git describe > ${VERSION_FILE}'
                . sprintf(' && git commit -am ":pencil: update version file - %s"', $version))
            . ' && git push --tags && git push --all && git checkout ${DEVELOP_BRANCH}'
            . (empty($versionFile) ? '' : ' && git merge main && git push');
        $process = $this->processRunner->runShellCommand($commandText, [
            'DEVELOP_BRANCH' => 'develop',
            'VERSION_FILE' => $versionFile,
            'VERSION' => $version,
        ]);
        if (!$process->isSuccessful()) {
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

        $this->line($process->getOutput());

        return true;
    }

    /**
     * @throws ExceptionInterface
     */
    private function isGitFlowAvailable(): bool
    {
        $process = $this->processRunner->run(['git', 'flow', 'version']);
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

        $this->comment(sprintf('[i] Git Flow version: %s', $process->getOutput()));

        return true;
    }

    /**
     * @throws ExceptionInterface
     */
    private function getCurrentBranch(): ?string
    {
        $process = $this->processRunner->run(['git', 'branch', '--show-current']);
        if (!$process->isSuccessful()) {
            $this->comment('Failed to get current branch name.');
            $this->line(
                sprintf(
                    "%s - %s \n%s",
                    $process->getExitCode(),
                    $process->getExitCodeText(),
                    $process->getOutput()
                )
            );

            return null;
        }

        return trim($process->getOutput());
    }
}
