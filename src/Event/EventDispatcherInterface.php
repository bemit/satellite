<?php

namespace Satellite\Event;

use Psr\Container\ContainerInterface;

interface EventDispatcherInterface {

    public function __construct(EventListenerInterface $listener);

    public function useContainer(ContainerInterface $container);
}
