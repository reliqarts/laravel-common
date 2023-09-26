<?php

declare(strict_types=1);

namespace ReliqArts\Contract;

use InvalidArgumentException;
use Psr\Log\LoggerInterface;

interface LoggerFactory
{
    public const DEFAULT_EXTENSION = 'log';

    /**
     * @throws InvalidArgumentException
     */
    public function create(
        string $loggerName,
        string $logFileBasename,
        string $logFileExtension = self::DEFAULT_EXTENSION
    ): Logger;

    public function createWithInternalLogger(LoggerInterface $logger): Logger;
}
