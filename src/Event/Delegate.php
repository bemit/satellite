<?php

namespace Satellite\Event;

class Delegate implements DelegateInterface {
    protected $handler;
    protected $evt;

    /**
     * Delegate constructor.
     *
     * @param callable $handler
     * @param object $evt
     */
    public function __construct($handler, $evt) {
        $this->handler = $handler;
        $this->evt = $evt;
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
