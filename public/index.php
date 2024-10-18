<?php

declare(strict_types=1);

use App\Kernel;

$startTime = \microtime(true);
$startMemory = \memory_get_usage();

require_once \dirname(__DIR__).'/vendor/autoload_runtime.php';

\define('BENCHMARK_START_TIME', $startTime);
\define('BENCHMARK_START_MEMORY', $startMemory);

return function (array $context) use ($startTime, $startMemory) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
