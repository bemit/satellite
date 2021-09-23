<?php declare(strict_types=1);

namespace Satellite;

interface SatelliteAppInterface {
    public function launch(bool $cli): void;

    public function isCLI(): bool;
}
