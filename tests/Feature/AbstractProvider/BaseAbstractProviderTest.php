<?php

namespace TheTreehouse\Relay\Tests\Feature\AbstractProvider;

use TheTreehouse\Relay\AbstractProvider;
use TheTreehouse\Relay\Tests\TestCase;

abstract class BaseAbstractProviderTest extends TestCase
{
    protected function configureRelay()
    {
        parent::configureRelay();

        config(['relay.providers.hub_fake.contact_fields' => [
            'first_name' => 'firstName',
            'last_name' => 'lastName',
        ]]);

        config(['relay.providers.hub_fake.organization_fields' => [
            'name' => 'companyName',
        ]]);
    }

    protected function newAbstractProviderImplementation(): AbstractProvider
    {
        return new HubFakeRelay;
    }
}

class HubFakeRelay extends AbstractProvider
{
    // Increase the visibility of the properties for testing
    public $name;
    public $configKey;
    public $supportsContacts = true;
    public $supportsOrganizations = true;
    public $contactModelColumn;
    public $organizationModelColumn;
}
