<?php

namespace TheTreehouse\Relay\Tests\Feature;

use TheTreehouse\Relay\Facades\Relay;
use TheTreehouse\Relay\Tests\Fixtures\Models\Contact;
use TheTreehouse\Relay\Tests\Fixtures\Models\Organization;
use TheTreehouse\Relay\Tests\TestCase;

class RelayTest extends TestCase
{
    public function configureRelay()
    {
        if ($this->getName() !== 'test_it_uses_empty_model_configuration') {
            return parent::configureRelay();
        }
    }
    
    public function test_it_uses_empty_model_configuration()
    {
        $this->assertNull(Relay::contactModel());
        $this->assertNull(Relay::organizationModel());
    }

    public function test_it_uses_model_configuration()
    {
        $this->assertEquals(Contact::class, Relay::contactModel());
        $this->assertEquals(Organization::class, Relay::organizationModel());
    }
}