<?php

declare(strict_types=1);

namespace ReliqArts\Factory;

use InvalidArgumentException;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger as MonologLogger;
use Psr\Log\LoggerInterface;
use ReliqArts\Contract\Logger as LoggerContract;
use ReliqArts\Contract\LoggerFactory as LoggerFactoryContract;
use ReliqArts\Service\Logger;

/**
 * @codeCoverageIgnore
 */
final class LoggerFactory implements LoggerFactoryContract
{
    /**
     * @throws InvalidArgumentException
     */
    public function create(
        string $loggerName,
        string $logFileBasename,
        string $logFileExtension = self::DEFAULT_EXTENSION
    ): LoggerContract {
        $logFilePath = storage_path(sprintf('logs/%s.%s', $logFileBasename, $logFileExtension));
        $internalLogger = new MonologLogger($loggerName);
        $internalLogger->pushHandler(new StreamHandler($logFilePath, Level::Debug));

        return $this->createWithInternalLogger($internalLogger);
    }

    public function createWithInternalLogger(LoggerInterface $logger): LoggerContract
    {
        return new Logger($logger);
    }
}
