<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/mock/EventListenerEventParent.php';
require_once __DIR__ . '/mock/EventListenerEvent.php';
require_once __DIR__ . '/mock/EventListenerEventStoppable.php';
require_once __DIR__ . '/mock/EventListenerEventMore.php';
require_once __DIR__ . '/mock/InvokerMock.php';

final class EventDispatcherTest extends TestCase {
    public function testDispatchingEvent(): void {
        $listener = new \Satellite\Event\EventListener();
        $listener->on(EventListenerEvent::class, static fn(EventListenerEvent $evt) => $evt->setTestData('is-modified'));
        $dispatcher = new \Satellite\Event\EventDispatcher($listener, new InvokerMock());
        $evt = new EventListenerEvent();
        /**
         * @var EventListenerEvent $res
         */
        $res = $dispatcher->dispatch($evt);
        $this->assertEquals(
            'is-modified',
            $res->getTestData()
        );
    }

    public function testDispatchingEventMultipleListener(): void {
        $listener = new \Satellite\Event\EventListener();
        $listener->on(EventListenerEvent::class, static fn(EventListenerEvent $evt) => $evt->setTestData(($evt->getTestData() ?? 0) + 1));
        $listener->on(EventListenerEvent::class, static fn(EventListenerEvent $evt) => $evt->setTestData(($evt->getTestData() ?? 0) + 1));
        $listener->on(EventListenerEvent::class, static fn(EventListenerEvent $evt) => $evt->setTestData(($evt->getTestData() ?? 0) + 1));
        $dispatcher = new \Satellite\Event\EventDispatcher($listener, new InvokerMock());
        $evt = new EventListenerEvent();
        /**
         * @var EventListenerEvent $res
         */
        $res = $dispatcher->dispatch($evt);
        $this->assertEquals(3, $res->getTestData());
    }

    public function testDispatchingEventMultipleListenerWithStoppable(): void {
        $listener = new \Satellite\Event\EventListener();
        $listener->on(EventListenerEventStoppable::class, static fn(EventListenerEventStoppable $evt) => $evt->setTestData(($evt->getTestData() ?? 0) + 1));
        $listener->on(EventListenerEventStoppable::class, static fn(EventListenerEventStoppable $evt) => $evt->setTestData(($evt->getTestData() ?? 0) + 1)->stopPropagation());
        $listener->on(EventListenerEventStoppable::class, static fn(EventListenerEventStoppable $evt) => $evt->setTestData(($evt->getTestData() ?? 0) + 1));
        $dispatcher = new \Satellite\Event\EventDispatcher($listener, new InvokerMock());
        $evt = new EventListenerEventStoppable();
        /**
         * @var EventListenerEventStoppable $res
         */
        $res = $dispatcher->dispatch($evt);
        $this->assertEquals(2, $res->getTestData());
    }

    public function testDispatchingEventWithDelegateWithResult(): void {
        $listener = new \Satellite\Event\EventListener();
        $listener->on(
            EventListenerEvent::class,
            static fn(EventListenerEvent $evt) => (new \Satellite\Event\Delegate())
                ->setHandler(static fn() => new EventListenerEventMore())
                ->setEvent($evt)
        );
        $dispatcher = new \Satellite\Event\EventDispatcher($listener, new InvokerMock());
        $evt = new EventListenerEvent();
        $res = $dispatcher->dispatch($evt);
        $this->assertEquals(EventListenerEventMore::class, get_class($res));
    }

    public function testDispatchingEventWithDelegateWithoutResult(): void {
        $listener = new \Satellite\Event\EventListener();
        $listener->on(
            EventListenerEvent::class,
            static fn(EventListenerEvent $evt) => (new \Satellite\Event\Delegate())
                ->setHandler(static fn() => null)
                ->setEvent($evt->setTestData('is-delegated-but-null-result'))
        );
        $dispatcher = new \Satellite\Event\EventDispatcher($listener, new InvokerMock());
        $evt = new EventListenerEvent();
        /**
         * @var EventListenerEvent $res
         */
        $res = $dispatcher->dispatch($evt);
        $this->assertEquals('is-delegated-but-null-result', $evt->getTestData());
    }
}
