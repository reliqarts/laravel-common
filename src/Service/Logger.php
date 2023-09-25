<?php

declare(strict_types=1);

namespace ReliqArts\Service;

use Psr\Log\LoggerInterface;
use ReliqArts\Contract\Logger as LoggerContract;
use Stringable;

readonly class Logger implements LoggerContract
{
    public function __construct(private LoggerInterface $internalLogger)
    {
    }

    public function emergency(Stringable|string $message, array $context = []): void
    {
        $this->internalLogger->emergency($message, $context);
    }

    public function alert(Stringable|string $message, array $context = []): void
    {
        $this->internalLogger->alert($message, $context);
    }

    public function critical(Stringable|string $message, array $context = []): void
    {
        $this->internalLogger->critical($message, $context);
    }

    public function error(Stringable|string $message, array $context = []): void
    {
        $this->internalLogger->error($message, $context);
    }

    public function warning(Stringable|string $message, array $context = []): void
    {
        $this->internalLogger->warning($message, $context);
    }

    public function notice(Stringable|string $message, array $context = []): void
    {
        $this->internalLogger->notice($message, $context);
    }

    public function info(Stringable|string $message, array $context = []): void
    {
        $this->internalLogger->info($message, $context);
    }

    public function debug(Stringable|string $message, array $context = []): void
    {
        $this->internalLogger->debug($message, $context);
    }

    public function log($level, Stringable|string $message, array $context = []): void
    {
        $this->internalLogger->log($level, $message, $context);
    }
}
