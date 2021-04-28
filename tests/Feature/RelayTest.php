<?php

namespace TheTreehouse\Relay\Tests\Feature;

use TheTreehouse\Relay\Facades\Relay;
use TheTreehouse\Relay\Tests\TestCase;

class RelayTest extends TestCase
{
    public function test_it_uses_empty_model_configuration()
    {
        $this->assertNull(Relay::contactModel());
        $this->assertNull(Relay::organizationModel());
    }
}