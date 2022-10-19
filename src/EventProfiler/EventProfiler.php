<?php declare(strict_types=1);

namespace Satellite\EventProfiler;

class EventProfiler implements EventProfilerInterface {
    protected int $profile_report_id_latest = 0;
    protected ?int $profile_parent_id = null;
    protected int $level;
    protected EventProfilerReporterInterface $reporter;

    public function __construct(
        EventProfilerReporterInterface $reporter,
        int                            $level = 1,
    ) {
        $this->level = $level;
        $this->reporter = $reporter;
    }

    public function getReporter(): EventProfilerReporterInterface {
        return $this->reporter;
    }

    protected function getNameOfHandler(callable|array|string $callable): string {
        if(is_string($callable)) {
            return $callable;
        }
        if(is_array($callable)) {
            if(is_object($callable[0])) {
                return get_class($callable[0]) . '->' . $callable[1];
            }
            return $callable[0] . '->' . $callable[1];
        }
        if($callable instanceof \Closure) {
            return 'Closure';
        }
        if(is_object($callable)) {
            return get_class($callable);
        }
        return 'unknown';
    }

    /**
     * @inheritdoc
     */
    public function run(object $event, callable|array|string $event_handler, callable $dispatcher): mixed {
        $usage_start = getrusage();
        $ts = microtime(true);
        $id = ++$this->profile_report_id_latest;
        $current_parent = $this->profile_parent_id;
        $this->profile_parent_id = $id;

        $result = $dispatcher();

        $usage_end = getrusage();
        $report = [
            'id' => $id,
            'parent' => $current_parent,
            'name' => get_class($event),
            'handler' => $this->getNameOfHandler($event_handler),
            'time_start' => $ts,
            'time_end' => microtime(true),
            'utime_ms' => $this->formatRuntime($usage_end, $usage_start, "utime"),
            'stime_ms' => $this->formatRuntime($usage_end, $usage_start, "stime"),
        ];
        if($this->level >= 1) {
            $report['maxrss'] = $this->diffRuntime($usage_end, $usage_start, "maxrss");
        }
        if($this->level >= 2) {
            $report['oublock'] = $this->diffRuntime($usage_end, $usage_start, "oublock");
            $report['inblock'] = $this->diffRuntime($usage_end, $usage_start, "inblock");
        }
        if($this->level >= 3) {
            $report['minflt'] = $this->diffRuntime($usage_end, $usage_start, "minflt");
            $report['majflt'] = $this->diffRuntime($usage_end, $usage_start, "majflt");
        }
        $this->reporter->add($report);
        $this->profile_parent_id = $current_parent;

        return $result;
    }

    private function diffRuntime($end, $start, $index): float|int {
        return $end["ru_$index"] - $start["ru_$index"];
    }

    private function formatRuntime($end, $start, $index): float|int {
        return ($end["ru_$index.tv_sec"] * 1000 + intval($end["ru_$index.tv_usec"] / 1000))
            - ($start["ru_$index.tv_sec"] * 1000 + intval($start["ru_$index.tv_usec"] / 1000));
    }
}
