<?php

namespace Satellite\Event;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

interface EventDispatcherInterface {

    public function __construct(ListenerProviderInterface $listener);

    public function useContainer(ContainerInterface $container);
}
