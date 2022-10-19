<?php declare(strict_types=1);

namespace Satellite\EventProfiler;

use JetBrains\PhpStorm\ArrayShape;

interface EventProfilerReporterInterface {
    /**
     * @param array $report
     * @return void
     */
    public function add(
        #[ArrayShape([
            'id' => 'int',
            'parent' => 'int',
            'name' => 'string',
            'handler' => 'string',
            'time_start' => 'float',
            'time_end' => 'float',
            'utime_ms' => 'int',
            'stime_ms' => 'int',
            'maxrss' => 'int',
            'oublock' => 'int',
            'inblock' => 'int',
            'minflt' => 'int',
            'majflt' => 'int',
        ])]
        array $report,
    ): void;
}
