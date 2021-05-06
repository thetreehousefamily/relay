<?php

namespace TheTreehouse\Relay\Tests\Feature\AbstractProvider;

use Illuminate\Support\Str;
use TheTreehouse\Relay\Facades\Relay;
use TheTreehouse\Relay\Tests\Contracts\UsingFakeRelay;
use TheTreehouse\Relay\Tests\Fixtures\Models\Contact;

class IncomingOperationsTest extends BaseAbstractProviderTest implements UsingFakeRelay
{
    public function test_it_does_not_process_created_contact_if_not_supported_by_application()
    {
        Relay::setSupportsContacts(false);
        
        $provider = $this->newAbstractProviderImplementation();

        $this->assertFalse($provider->createdContact('foo', ['foo' => 'bar']));
    }

    public function test_it_does_not_process_created_contact_if_not_supported_by_provider()
    {
        $provider = $this->newAbstractProviderImplementation();

        $provider->supportsContacts = false;

        $this->assertFalse($provider->createdContact('foo', ['foo' => 'bar']));
    }

    public function test_it_creates_contact_from_provider()
    {
        $provider = $this->newAbstractProviderImplementation();

        $model = $provider->createdContact($id = 'hub_fake_id_'.Str::random(), ['name' => 'Josephine']);

        $this->assertInstanceOf(Contact::class, $model);

        $this->assertEquals($id, $model->hub_fake_id);
        $this->assertEquals('Josephine', $model->name);
    }

    public function test_it_creates_existing_contact_from_provider()
    {
        Contact::create([
            'hub_fake_id' => $id = 'hub_fake_id'.Str::random(),
            'name' => 'Josie'
        ]);

        $provider = $this->newAbstractProviderImplementation();

        $model = $provider->createdContact($id, ['name' => 'Josephine']);

        $this->assertInstanceOf(Contact::class, $model);
        $this->assertEquals($id, $model->hub_fake_id);
        $this->assertEquals('Josephine', $model->name);
    }
}