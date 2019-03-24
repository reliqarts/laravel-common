<?php

declare(strict_types=1);

namespace ReliqArts\Services;

use Monolog\Logger as BaseLogger;
use ReliqArts\Contracts\Logger as LoggerContract;

class Logger extends BaseLogger implements LoggerContract
{
}
