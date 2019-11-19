<?php

namespace Satellite\Event;

use Psr\EventDispatcher\StoppableEventInterface;

class EventDispatcher implements \Psr\EventDispatcher\EventDispatcherInterface {
    /**
     * @var \Satellite\Event\EventListener
     */
    public $listener;

    public function __construct() {
        $this->listener = new EventListener();
    }

    /**
     * Provide all relevant listeners with an event to process.
     *
     * @param object|StoppableEventInterface $event
     *   The object to process.
     *
     * @return object
     *   The Event that was passed, now modified by listeners.
     */
    public function dispatch($event) {
        $to_exec = $this->listener->getListenersForEvent($event);

        $stoppable = false;
        if($event instanceof StoppableEventInterface) {
            $stoppable = true;
        }

        foreach($to_exec as $exec) {
            // todo: di-autowire
            call_user_func($exec, $event);

            if($stoppable && $event->isPropagationStopped()) {
                break;
            }
        }

        return $event;
    }
}
