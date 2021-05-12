<?php

namespace TheTreehouse\Relay\Concerns;

use TheTreehouse\Relay\Dispatcher;

trait Relayable
{
    /**
     * Upsert the current state of this entity to the given Relay provider
     * 
     * @param \TheTreehouse\Relay\AbstractProvider|string|null $provider 
     * @return self
     */
    public function relay($provider = null)
    {
        app(Dispatcher::class)->processManualRelay($this, $provider);

        return $this;
    }
}
