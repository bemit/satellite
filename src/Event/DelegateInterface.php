<?php declare(strict_types=1);

namespace Satellite\Event;

interface DelegateInterface {

    /**
     * @param callable $handler
     */
    public function setHandler($handler);

    /**
     * @param object $evt
     */
    public function setEvent($evt);

    /**
     * @return callable
     */
    public function getHandler();

    /**
     * @return object
     */
    public function getEvent();
}
