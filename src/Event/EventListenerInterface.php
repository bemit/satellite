<?php

namespace Satellite\Event;

interface EventListenerInterface {
    /**
     * @param string $id of the event, typically the classname like `RouteEvent::class`
     * @param callable|string|array $listener anything the invoker can execute
     */
    public function on(string $id, $listener);
}
