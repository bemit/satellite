<?php declare(strict_types=1);

class EventListenerEventStoppable implements \Psr\EventDispatcher\StoppableEventInterface {
    use \Satellite\Event\StoppableEvent;
    
    private $test_data = null;

    public function setTestData($test_data) {
        $this->test_data = $test_data;
        return $this;
    }

    public function getTestData() {
        return $this->test_data;
    }
}
