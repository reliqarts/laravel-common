<?php

declare(strict_types=1);

namespace ReliqArts\Service;

use Monolog\Logger as BaseLogger;
use ReliqArts\Contract\Logger as LoggerContract;

class Logger extends BaseLogger implements LoggerContract
{
}
