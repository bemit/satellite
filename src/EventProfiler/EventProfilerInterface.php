<?php declare(strict_types=1);

namespace Satellite\EventProfiler;

interface EventProfilerInterface {
    /**
     * @param object $event the object to process.
     * @param callable|array|string $event_handler the to-be-called event handler
     * @param callable $dispatcher
     * @return mixed the result returned by the event-handler after dispatching
     */
    public function run(object $event, callable|array|string $event_handler, callable $dispatcher): mixed;

    /**
     * Get the registered reporter.
     *
     * @return EventProfilerReporterInterface
     */
    public function getReporter(): EventProfilerReporterInterface;
}
