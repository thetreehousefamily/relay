<?php

namespace TheTreehouse\Relay\Tests\Concerns;

use TheTreehouse\Relay\Relay;

trait InstantiatesRelay
{
    protected function newRelay(): Relay
    {
        return new Relay;
    }
}
