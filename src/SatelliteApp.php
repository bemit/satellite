<?php declare(strict_types=1);

namespace Satellite;

use Psr\EventDispatcher\EventDispatcherInterface;

class SatelliteApp implements SatelliteAppInterface {
    /**
     * @var EventDispatcherInterface
     */
    protected EventDispatcherInterface $dispatcher;
    protected bool $cli;

    public function __construct(EventDispatcherInterface $dispatcher) {
        $this->dispatcher = $dispatcher;
    }

    public function launch(bool $cli): void {
        $this->cli = $cli;
        $this->dispatcher->dispatch($this);
    }

    public function isCLI(): bool {
        return $this->cli;
    }
}
