<?php declare(strict_types=1);

namespace Satellite\Event;

trait StoppableEvent {
    protected bool $stopped = false;

    public function stopPropagation(): self {
        $this->stopped = true;
        return $this;
    }

    public function isPropagationStopped(): bool {
        return $this->stopped;
    }
}
