<?php

namespace TheTreehouse\Relay\Tests\Unit;

use PHPUnit\Framework\TestCase;
use TheTreehouse\Relay\AbstractProvider;
use TheTreehouse\Relay\Exceptions\InvalidModelException;
use TheTreehouse\Relay\Exceptions\InvalidProviderException;
use TheTreehouse\Relay\Relay;
use TheTreehouse\Relay\Tests\Fixtures\Models\Contact;
use TheTreehouse\Relay\Tests\Fixtures\Models\Organization;

class RelayTest extends TestCase
{
    public function test_it_rejects_invalid_provider_registrations()
    {
        $invalidProvider = new class {};

        $this->expectException(InvalidProviderException::class);

        Relay::registerProvider(get_class($invalidProvider));
    }

    public function test_it_registers_valid_providers()
    {
        $validProvider = $this->createMock(AbstractProvider::class);

        Relay::registerProvider(get_class($validProvider));

        $this->assertContains(get_class($validProvider), Relay::getRegisteredProviders());
    }

    public function test_it_rejects_invalid_contact_model_registrations()
    {
        $invalidModel = new class {};

        $this->expectException(InvalidModelException::class);

        Relay::useContactModel(get_class($invalidModel));
    }

    public function test_it_registers_valid_contact_models()
    {
        Relay::useContactModel(Contact::class);

        $this->assertEquals(Contact::class, Relay::contactModel());
    }

    public function test_it_rejects_invalid_organization_model_registrations()
    {
        $invalidModel = new class {};

        $this->expectException(InvalidModelException::class);

        Relay::useOrganizationModel(get_class($invalidModel));
    }

    public function test_it_registers_valid_organization_models()
    {
        Relay::useOrganizationModel(Organization::class);

        $this->assertEquals(Organization::class, Relay::organizationModel());
    }
}