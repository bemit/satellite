<?php

namespace Satellite\Event;

trait StoppableEvent {
    protected $stopped = false;

    public function stopPropagation() {
        $this->stopped = true;
    }

    public function isPropagationStopped(): bool {
        return $this->stopped;
    }
}
