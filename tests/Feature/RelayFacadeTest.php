<?php

namespace TheTreehouse\Relay\Tests\Feature;

use TheTreehouse\Relay\Facades\Relay as RelayFacade;
use TheTreehouse\Relay\Relay;
use TheTreehouse\Relay\Tests\TestCase;

class RelayFacadeTest extends TestCase
{
    public function test_it_resolves_to_relay_instance()
    {
        $this->assertInstanceOf(Relay::class, RelayFacade::getFacadeRoot());
    }
}