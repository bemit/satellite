<?php declare(strict_types=1);

namespace Satellite\EventProfiler;

use JetBrains\PhpStorm\ArrayShape;

/**
 * A reporter for the EventProfiler that writes all event reports directly into the log with `error_log`
 */
class EventProfilerReporterLog implements EventProfilerReporterInterface {
    /**
     * @inheritDoc
     */
    public function add(#[ArrayShape([
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
    ])] array $report): void {
        error_log(
            $report['time_start'] . '  ' .
            'parent: ' . ($report['parent'] === null ? '-' : $report['parent']) . '  ' .
            'id: ' . $report['id'] . '  ' .
            'name: ' . $report['name'] . '  ' .
            'handler: ' . $report['handler'] . '  ' .
            'utime: ' . $report['utime_ms'] . 'ms  ' .
            'stime: ' . $report['stime_ms'] . 'ms  '
        );
    }
}
