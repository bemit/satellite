<?php

namespace Satellite\Event;

class Delegate implements DelegateInterface {
    protected $handler;
    protected $evt;

    /**
     * @param callable $handler
     */
    public function setHandler($handler) {
        $this->handler = $handler;
    }

    /**
     * @param object $evt
     */
    public function setEvent($evt) {
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
