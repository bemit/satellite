<?php

namespace Satellite;

use Psr\Container\ContainerInterface;
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
class Event {

    protected $dispatcher;

    protected static $i;

    protected function __construct() {
        $this->dispatcher = new EventDispatcher(new EventListener());
    }

    /**
     * @return static|self
     */
    protected static function i() {
        if(null === static::$i) {
            static::$i = new static();
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
    public static function dispatcher() {
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
     */
    public static function dispatch($event) {
        static::i()->dispatcher->dispatch($event);
    }
}
