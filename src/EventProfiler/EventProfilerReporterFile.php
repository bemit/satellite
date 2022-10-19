<?php declare(strict_types=1);

namespace Satellite\EventProfiler;

use JetBrains\PhpStorm\ArrayShape;

/**
 * A reporter for the EventProfiler that writes all event reports into a JSON file.
 */
class EventProfilerReporterFile implements EventProfilerReporterInterface {
    protected $event_reports = [];
    protected ?array $profile_file = null;

    public function __construct(
        #[ArrayShape(['dir' => 'string', 'pattern' => 'string', 'prefix' => 'string', 'suffix' => 'string'])]
        array $file,
    ) {
        $this->profile_file = $file;
    }

    public function __destruct() {
        $this->saveToFile();
    }

    private function saveToFile(): void {
        if(!$this->profile_file || empty($this->event_reports)) {
            return;
        }
        if(!isset($this->profile_file['dir'], $this->profile_file['pattern'])) {
            throw new \RuntimeException('EventProfiler requires `dir` and `pattern` to save file');
        }
        $dir = $this->profile_file['dir'];
        if(!is_dir($dir) && !mkdir($dir, 0775, true)) {
            throw new \RuntimeException('profile file dir can not be created: ' . $dir);
        }
        $name = (new \DateTime('now', new \DateTimeZone('UTC')))->format($this->profile_file['pattern']);
        $prefix = $this->profile_file['prefix'] ?? '';
        $suffix = $this->profile_file['suffix'] ?? '';
        file_put_contents(
            $dir . '/' . $prefix . $name . $suffix . '.json',
            json_encode(
                [
                    'events' => $this->event_reports,
                ], JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR
            ),
        );
    }

    public function getProfileReport(): array {
        return $this->event_reports;
    }

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
        $this->event_reports[] = $report;
    }
}
