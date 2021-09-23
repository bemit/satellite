<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/mock/EventListenerEventParent.php';
require_once __DIR__ . '/mock/EventListenerEvent.php';
require_once __DIR__ . '/mock/EventListenerEventMore.php';

final class EventListenerTest extends TestCase {
    public function testGetListenersForEventObject(): void {
        $listener = new \Satellite\Event\EventListener();
        $listener->on(EventListenerEvent::class, static fn() => 'test-event');
        $evt = new EventListenerEvent();
        $listeners = $listener->getListenersForEvent($evt);
        $this->assertEquals(1, count($listeners));
        if(isset($listeners[0])) {
            $this->assertEquals(
                'test-event',
                $listeners[0]()
            );
        }
    }

    public function testGetParentListenersForEventObject(): void {
        $listener = new \Satellite\Event\EventListener();
        // also testing on order here, e.g. the `parent` must be after the events of the same class in the found listeners
        $listener->on(EventListenerEventParent::class, static fn() => 'test-event-parent');
        $listener->on(EventListenerEventMore::class, static fn() => 'some-other');
        $listener->on(EventListenerEvent::class, static fn() => 'test-event');
        $evt = new EventListenerEvent();
        $listeners = $listener->getListenersForEvent($evt);
        $this->assertEquals(2, count($listeners));
        if(isset($listeners[0])) {
            $this->assertEquals(
                'test-event',
                $listeners[0]()
            );
        }
        if(isset($listeners[1])) {
            $this->assertEquals(
                'test-event-parent',
                $listeners[1]()
            );
        }
    }

    public function testGetListenersForUnknownEvent(): void {
        $listener = new \Satellite\Event\EventListener();
        $listener->on(EventListenerEvent::class, static fn() => 'test-event');
        $evt = new EventListenerEventMore();
        $listeners = $listener->getListenersForEvent($evt);
        $this->assertEquals(0, count($listeners));
    }
}
