<?php

namespace Satellite\Event;

use Invoker\Invoker;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

class EventDispatcher implements \Psr\EventDispatcher\EventDispatcherInterface, EventDispatcherInterface {
    /**
     * @var \Psr\EventDispatcher\ListenerProviderInterface
     */
    public $listener;
    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var \Invoker\Invoker
     */
    protected $invoker;

    public function __construct(ListenerProviderInterface $listener) {
        $this->listener = $listener;
        $this->invoker = new Invoker();
    }

    public function useContainer(ContainerInterface $container) {
        if($this->container) {
            // only one-time
            return;
        }

        $this->container = $container;

        // setup invoker with container and resolvers that should be used
        $this->invoker = new Invoker(null, $container);
        $this->invoker->getParameterResolver()
                      ->prependResolver(
                          new EventDispatcherTypeHintContainerResolver($container)
                      );
    }

    /**
     * Provide all relevant listeners with an event to process.
     *
     * @param object|StoppableEventInterface $event
     *   The object to process.
     *
     * @throws \Invoker\Exception\InvocationException
     * @throws \Invoker\Exception\NotCallableException
     * @throws \Invoker\Exception\NotEnoughParametersException
     *
     * @return object|\Psr\EventDispatcher\object
     *   The Event that was passed, now modified by listeners.
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
     * @param $event_handler
     * @param $event
     *
     * @throws \Invoker\Exception\InvocationException
     * @throws \Invoker\Exception\NotCallableException
     * @throws \Invoker\Exception\NotEnoughParametersException
     * @return mixed|\Satellite\Event\DelegateInterface
     */
    protected function execute($event_handler, $event) {
        if(empty($event_handler)) {
            throw new \RuntimeException('Empty event_handler');
        }
        $result = $this->invoke($event_handler, $event);

        if(is_subclass_of($result, DelegateInterface::class) && $result->getHandler() && $result->getEvent()) {
            $res = $this->invoke($result->getHandler(), $result->getEvent());

            // delegation enables to un-dock the result, e.g. add logic without relying that it passes back anything, when something it is expected to be compatible with the `Event` it has received
            if(isset($res)) {
                return $res;
            }

            return $result->getEvent();
        }

        return $result;
    }

    /**
     * @param $event_handler
     * @param $event
     *
     * @throws \Invoker\Exception\InvocationException
     * @throws \Invoker\Exception\NotCallableException
     * @throws \Invoker\Exception\NotEnoughParametersException
     * @return mixed|\Satellite\Event\DelegateInterface
     */
    protected function invoke($event_handler, $event) {
        return $this->invoker->call($event_handler, [$event]);
    }
}
