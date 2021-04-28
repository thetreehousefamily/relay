<?php

namespace TheTreehouse\Relay\Tests\Unit;

use PHPUnit\Framework\TestCase;
use TheTreehouse\Relay\AbstractProvider;
use TheTreehouse\Relay\Exceptions\InvalidModelException;
use TheTreehouse\Relay\Exceptions\InvalidProviderException;
use TheTreehouse\Relay\Tests\Concerns\InstantiatesRelay;
use TheTreehouse\Relay\Tests\Fixtures\Models\Contact;
use TheTreehouse\Relay\Tests\Fixtures\Models\Organization;

class RelayUnitTest extends TestCase
{
    use InstantiatesRelay;

    public function test_it_rejects_invalid_provider_registrations()
    {
        $invalidProvider = new class {
        };

        $this->expectException(InvalidProviderException::class);

        $this->newRelay()->registerProvider(get_class($invalidProvider));
    }

    public function test_it_registers_valid_providers()
    {
        $validProvider = $this->createMock(AbstractProvider::class);

        $relay = $this->newRelay();

        $relay->registerProvider(get_class($validProvider));

        $this->assertContains(get_class($validProvider), $relay->getRegisteredProviders());
    }

    public function test_it_does_not_re_register_valid_providers()
    {
        $validProvider = $this->createMock(AbstractProvider::class);

        $relay = $this->newRelay();

        $relay
            ->registerProvider(get_class($validProvider))
            ->registerProvider(get_class($validProvider));

        $this->assertEquals([get_class($validProvider)], $relay->getRegisteredProviders());
    }

    public function test_it_rejects_invalid_contact_model_registrations()
    {
        $invalidModel = new class {
        };

        $this->expectException(InvalidModelException::class);

        $this->newRelay()->useContactModel(get_class($invalidModel));
    }

    public function test_it_registers_valid_contact_models()
    {
        $relay = $this->newRelay();

        $relay->useContactModel(Contact::class);

        $this->assertEquals(Contact::class, $relay->contactModel());
    }

    public function test_it_rejects_invalid_organization_model_registrations()
    {
        $invalidModel = new class {
        };

        $this->expectException(InvalidModelException::class);

        $this->newRelay()->useOrganizationModel(get_class($invalidModel));
    }

    public function test_it_registers_valid_organization_models()
    {
        $relay = $this->newRelay();

        $relay->useOrganizationModel(Organization::class);

        $this->assertEquals(Organization::class, $relay->organizationModel());
    }

    public function test_it_returns_correct_contacts_supported_value()
    {
        $relay = $this->newRelay();

        $this->assertFalse($relay->supportsContacts());
        
        $relay->useContactModel(Contact::class);

        $this->assertTrue($relay->supportsContacts());
    }

    public function test_it_returns_correct_organizations_supported_value()
    {
        $relay = $this->newRelay();

        $this->assertFalse($relay->supportsOrganizations());
        
        $relay->useOrganizationModel(Organization::class);

        $this->assertTrue($relay->supportsOrganizations());
    }
}
