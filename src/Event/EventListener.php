<?php declare(strict_types=1);

namespace Satellite\Event;

use Psr\EventDispatcher\ListenerProviderInterface;

class EventListener implements EventListenerInterface, ListenerProviderInterface {

    protected $ids = [];
    protected $store = [];

    /**
     * @param string $id of the event, typically the classname like `RouteEvent::class`
     * @param callable|string|array $listener anything the invoker can execute
     */
    public function on(string $id, $listener) {
        if(!isset($this->store[$id]) || !is_array($this->store[$id])) {
            $this->store[$id] = [];
        }
        $this->ids[$id] = true;
        $this->store[$id][] = $listener;
    }

    /**
     * @param object $event
     *   An event for which to return the relevant listeners.
     *
     * @return iterable[callable]
     *   An iterable (array, iterator, or generator) of callables.  Each
     *   callable MUST be type-compatible with $event.
     */
    public function getListenersForEvent($event): iterable {
        $class = get_class($event);

        $events = [];
        if(isset($this->store[$class])) {
            array_push($events, ...$this->store[$class]);
        }
        $parents = class_parents($event);
        foreach($parents as $parent) {
            if(isset($this->store[$parent])) {
                array_push($events, ...$this->store[$parent]);
            }
        }

        return $events;
    }
}
