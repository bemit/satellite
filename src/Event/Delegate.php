<?php declare(strict_types=1);

namespace Satellite\Event;

class Delegate implements DelegateInterface {
    protected $handler;
    protected $evt;

    /**
     * @param callable $handler
     */
    public function setHandler($handler): self {
        $this->handler = $handler;
        return $this;
    }

    /**
     * @param object $evt
     */
    public function setEvent($evt): self {
        $this->evt = $evt;
        return $this;
    }

    /**
     * @return callable
     */
    public function getHandler() {
        return $this->handler;
    }

    /**
     * @return object
     */
    public function getEvent() {
        return $this->evt;
    }
}
