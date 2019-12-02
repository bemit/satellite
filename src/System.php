<?php

namespace Satellite;

class System {
    /**
     * @var string $starttime
     */
    public $starttime;
    /**
     * @var bool $verbose verbose
     */
    public $verbose;

    public function launch() {
        $this->starttime = date('Y-m-d H:i:s');

        $launch = new SystemLaunchEvent();
        $launch->cli = PHP_SAPI === 'cli';

        Event::dispatch($launch);
    }
}
