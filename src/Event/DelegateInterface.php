<?php

namespace Satellite\Event;

interface DelegateInterface {

    /**
     * Delegate the handling of an event to another callable
     *
     * @param callable $handler that will handle the evt
     * @param object $evt
     */
    public function __construct($handler, $evt);

    /**
     * @return callable
     */
    public function getHandler();

    /**
     * @return object
     */
    public function getEvent();
}
