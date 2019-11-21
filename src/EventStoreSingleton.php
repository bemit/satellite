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
interface EventStoreSingleton {
    /**
     * @var string $class replaces the singleton class with another
     */
    public static function setSingletonClass($class);

    /**
     * Binds any PSR-11 Container to the event store
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public static function useContainer(ContainerInterface $container);

    /**
     * @return \Psr\EventDispatcher\EventDispatcherInterface
     */
    public static function dispatcher(): EventDispatcherInterface;

    /**
     * @param string $event
     * @param callable $listener
     */
    public static function on($event, $listener);

    /**
     * @param object $event
     */
    public static function dispatch($event);
}
