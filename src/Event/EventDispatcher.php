<?php declare(strict_types=1);

namespace Satellite\Event;

use Invoker\Exception\InvocationException;
use Invoker\Exception\NotCallableException;
use Invoker\Exception\NotEnoughParametersException;
use Invoker\InvokerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use Satellite\EventProfiler\EventProfilerInterface;

class EventDispatcher implements \Psr\EventDispatcher\EventDispatcherInterface {
    /**
     * @var ListenerProviderInterface
     */
    protected $listener;
    /**
     * @var InvokerInterface
     */
    protected $invoker;
    protected ?EventProfilerInterface $profiler;

    public function __construct(
        ListenerProviderInterface $listener,
        InvokerInterface          $invoker,
        ?EventProfilerInterface   $profiler = null,
    ) {
        $this->listener = $listener;
        $this->invoker = $invoker;
        $this->profiler = $profiler;
    }

    /**
     * Provide all relevant listeners with an event to process.
     *
     * @param object|StoppableEventInterface $event The object to process.
     *
     * @return object The Event that was passed, now modified by listeners.
     * @throws NotCallableException
     * @throws NotEnoughParametersException
     *
     * @throws InvocationException
     */
    public function dispatch($event): object {
        $to_exec = $this->listener->getListenersForEvent($event);

        foreach($to_exec as $event_handler) {
            $event = $this->execute($event, $event_handler);

            if($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                break;
            }
        }

        return $event;
    }

    /**
     * Executes any event_handler with the given event
     * @param callable|string|array $event_handler
     * @param object $event
     * @param mixed $fallback_result
     *
     * @return object
     *
     * @throws NotEnoughParametersException
     * @throws InvocationException
     * @throws NotCallableException
     */
    protected function execute(object $event, callable|string|array $event_handler, mixed $fallback_result = null): object {
        if(empty($event_handler)) {
            throw new \RuntimeException('Empty event_handler');
        }

        $executor = fn() => $this->invoker->call($event_handler, [$event]);
        $result =
            $this->profiler ?
                $this->profiler->run($event, $event_handler, $executor) :
                $executor();

        if(is_subclass_of($result, DelegateInterface::class) && $result->getHandler() && $result->getEvent()) {
            return $this->execute(
                $result->getEvent(),
                $result->getHandler(),
                // delegation enables to un-dock the result,
                // e.g. add logic without relying that it passes back anything, when something it is expected to be compatible with the `Event` it has received
                $result->getEvent(),
            );
        }

        return $result ?? $fallback_result;
    }
}
