<?php

namespace Satellite;

use Psr\EventDispatcher\StoppableEventInterface;
use Satellite\Event\StoppableEvent;

class SystemLaunchEvent implements StoppableEventInterface {
    use StoppableEvent;

    /**
     * @var bool
     */
    public $cli = false;
}
