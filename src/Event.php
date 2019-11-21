<?php

namespace Satellite;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Satellite\Event\EventDispatcher;
use Satellite\Event\EventListener;

/**
 * Singleton Interface for a Satellite App Event Storage.
 *
 * Register new listener:
 * Event::on(EventClass:class, static function(EventClass $evt) {
 * };
 *
 * @package Satellite
 */
class Event implements EventStoreSingleton {

    protected $dispatcher;

    protected static $i;

    /**
     * @var string replace with other event singleton class
     */
    protected static $singleton_class = Event::class;

    /**
     * @var string $class replaces the singleton class with another
     */
    public static function setSingletonClass($class) {
        static::$singleton_class = $class;
    }

    protected function __construct() {
        $this->dispatcher = new EventDispatcher(new EventListener());
    }

    /**
     * @return static|self
     */
    protected static function i() {
        if(null === static::$i) {
            $class = static::$singleton_class;
            static::$i = new $class();
        }

        return static::$i;
    }

    /**
     * Binds any PSR-11 Container to the event store
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public static function useContainer(ContainerInterface $container) {
        static::i()->dispatcher->useContainer($container);
    }

    /**
     * @return \Psr\EventDispatcher\EventDispatcherInterface
     */
    public static function dispatcher(): EventDispatcherInterface {
        return static::i()->dispatcher;
    }

    /**
     * @param string $event
     * @param callable $listener
     */
    public static function on($event, $listener) {
        static::i()->dispatcher->listener->on($event, $listener);
    }

    /**
     * @param object $event
     *
     * @throws \Invoker\Exception\InvocationException
     * @throws \Invoker\Exception\NotCallableException
     * @throws \Invoker\Exception\NotEnoughParametersException
     */
    public static function dispatch($event) {
        static::i()->dispatcher->dispatch($event);
    }
}
