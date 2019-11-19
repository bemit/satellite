<?php

namespace Satellite;

use Satellite\Event\EventDispatcher;

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
        $this->dispatcher = new EventDispatcher();
    }

    /**
     * @return static|self
     */
    protected static function i() {
        if(null === static::$i) {
            if(__CLASS__ !== static::class) {
                $tmp = static::class;
                static::$i = new $tmp;
            } else {
                static::$i = new self;
            }
        }

        return static::$i;
    }

    public static function on($id, $listener) {
        static::i()->dispatcher->listener->on($id, $listener);
    }

    public static function dispatch($event) {
        static::i()->dispatcher->dispatch($event);
    }
}
