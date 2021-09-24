<?php declare(strict_types=1);

namespace Satellite\Event;

use Invoker\InvokerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

class EventDispatcher implements \Psr\EventDispatcher\EventDispatcherInterface {
    /**
     * @var ListenerProviderInterface
     */
    protected $listener;
    /**
     * @var InvokerInterface
     */
    protected $invoker;

    public function __construct(ListenerProviderInterface $listener, InvokerInterface $invoker) {
        $this->listener = $listener;
        $this->invoker = $invoker;
    }

    /**
     * Provide all relevant listeners with an event to process.
     *
     * @param object|StoppableEventInterface $event The object to process.
     *
     * @return object The Event that was passed, now modified by listeners.
     * @throws \Invoker\Exception\NotCallableException
     * @throws \Invoker\Exception\NotEnoughParametersException
     *
     * @throws \Invoker\Exception\InvocationException
     */
    public function dispatch($event) {
        $to_exec = $this->listener->getListenersForEvent($event);

        $stoppable = false;
        if($event instanceof StoppableEventInterface) {
            $stoppable = true;
        }

        foreach($to_exec as $exec) {
            $event = $this->execute($exec, $event);

            if($stoppable && $event->isPropagationStopped()) {
                break;
            }
        }

        return $event;
    }

    /**
     * Executes any event_handler with the given event
     * @param callable|string|array $event_handler
     * @param object $event
     *
     * @return object
     *
     * @throws \Invoker\Exception\NotEnoughParametersException
     * @throws \Invoker\Exception\InvocationException
     * @throws \Invoker\Exception\NotCallableException
     */
    protected function execute($event_handler, object $event): object {
        if(empty($event_handler)) {
            throw new \RuntimeException('Empty event_handler');
        }
        $result = $this->invoker->call($event_handler, [$event]);

        if(is_subclass_of($result, DelegateInterface::class) && $result->getHandler() && $result->getEvent()) {
            $res = $this->invoker->call($result->getHandler(), [$result->getEvent()]);

            // delegation enables to un-dock the result, e.g. add logic without relying that it passes back anything, when something it is expected to be compatible with the `Event` it has received
            return $res ?? $result->getEvent();
        }

        return $result;
    }
}
