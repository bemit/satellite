<?php

namespace Satellite;

class System {
    /**
     * @var string $starttime
     */
    public static $starttime;
    /**
     * @var bool $verbose verbose
     */
    public static $verbose;

    public function launch() {
        static::$starttime = date('Y-m-d H:i:s');

        $launch = new SystemLaunchEvent();
        $launch->cli = PHP_SAPI === 'cli';

        Event::dispatch($launch);
    }
}
