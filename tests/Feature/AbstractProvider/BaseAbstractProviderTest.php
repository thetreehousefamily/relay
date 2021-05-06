<?php

namespace TheTreehouse\Relay\Tests\Feature\AbstractProvider;

use TheTreehouse\Relay\AbstractProvider;
use TheTreehouse\Relay\Tests\TestCase;

abstract class BaseAbstractProviderTest extends TestCase
{
    protected function newAbstractProviderImplementation(): AbstractProvider
    {
        return new HubFakeRelay;
    }
}

class HubFakeRelay extends AbstractProvider
{
    // Increase the visibility of the properties for testing
    public $name;
    public $supportsContacts = true;
    public $supportsOrganizations = true;
    public $contactModelColumn;
    public $organizationModelColumn;
    public $createContactJob;
    public $createOrganizationJob;
    public $updateContactJob;
    public $updateOrganizationJob;
    public $deleteContactJob;
    public $deleteOrganizationJob;
}
