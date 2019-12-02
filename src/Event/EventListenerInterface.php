<?php

namespace Satellite\Event;

use Psr\EventDispatcher\ListenerProviderInterface;

interface EventListenerInterface extends ListenerProviderInterface {

    /**
     * @param string $id of the event, typically the classname like `RouteEvent::class`
     * @param callable|string|array $listener anything the invoker can execute
     */
    public function on($id, $listener);
}
