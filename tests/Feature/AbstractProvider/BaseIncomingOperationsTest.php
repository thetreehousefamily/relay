<?php

namespace TheTreehouse\Relay\Tests\Feature\AbstractProvider;

use Illuminate\Support\Str;
use TheTreehouse\Relay\Facades\Relay;
use TheTreehouse\Relay\Tests\Contracts\UsingFakeRelay;

abstract class BaseIncomingOperationsTest extends BaseAbstractProviderTest implements UsingFakeRelay
{
    /** @var string */
    protected $entity;

    /** @var string */
    protected $modelClass;

    private $supportOperations = [
        'created',
        'updated',
        'deleted'
    ];

    public function test_it_does_not_process_operation_if_not_supported_by_application()
    {
        Relay::{"setSupports{$this->ucEntityPlural()}"}(false);

        foreach($this->supportOperations as $operation) {
            $provider = $this->newAbstractProviderImplementation();
    
            $this->assertFalse($provider->{$operation.$this->ucEntity()}('foo', ['foo' => 'bar']));
        }
    }

    public function test_it_does_not_process_operation_if_not_supported_by_provider()
    {
        foreach($this->supportOperations as $operation) {
            $provider = $this->newAbstractProviderImplementation();

            $provider->{"supports{$this->ucEntityPlural()}"} = false;
    
            $this->assertFalse($provider->{$operation.$this->ucEntity()}('foo', ['foo' => 'bar']));
        }
    }

    public function test_it_creates_entity_from_provider()
    {
        $provider = $this->newAbstractProviderImplementation();

        $model = $provider->{"created{$this->ucEntity()}"}($id = 'hub_fake_id_'.Str::random(), ['name' => 'Josephine']);

        $this->assertInstanceOf($this->modelClass, $model);

        $this->assertEquals($id, $model->hub_fake_id);
        $this->assertEquals('Josephine', $model->name);
    }

    public function test_it_creates_existing_entity_from_provider()
    {
        $existing = $this->modelClass::create([
            'hub_fake_id' => $id = 'hub_fake_id'.Str::random(),
            'name' => 'Josie'
        ]);

        $provider = $this->newAbstractProviderImplementation();

        $model = $provider->{"created{$this->ucEntity()}"}($id, ['name' => 'Josephine']);

        $this->assertInstanceOf($this->modelClass, $model);
        $this->assertEquals($existing->id, $model->id);
        $this->assertEquals($id, $model->hub_fake_id);
        $this->assertEquals('Josephine', $model->name);
    }

    public function test_it_updates_entity_from_provider()
    {
        $existing = $this->modelClass::create([
            'hub_fake_id' => $id = 'hub_fake_id'.Str::random(),
            'name' => 'Josie'
        ]);

        $provider = $this->newAbstractProviderImplementation();

        $model = $provider->{"updated{$this->ucEntity()}"}($id, ['name' => 'Josephine']);

        $this->assertInstanceOf($this->modelClass, $model);
        $this->assertEquals($existing->id, $model->id);
        $this->assertEquals($id, $model->hub_fake_id);
        $this->assertEquals('Josephine', $model->name);
    }

    public function test_it_upserts_entity_from_provider()
    {
        $provider = $this->newAbstractProviderImplementation();

        $model = $provider->{"updated{$this->ucEntity()}"}($id = 'hub_fake_id_'.Str::random(), ['name' => 'Josephine']);

        $this->assertInstanceOf($this->modelClass, $model);

        $this->assertEquals($id, $model->hub_fake_id);
        $this->assertEquals('Josephine', $model->name);
    }

    public function test_it_deletes_entity_from_provider()
    {
        $this->modelClass::create([
            'hub_fake_id' => $id = 'hub_fake_id'.Str::random(),
            'name' => 'Josie'
        ]);

        $provider = $this->newAbstractProviderImplementation();

        $result = $provider->{"deleted{$this->ucEntity()}"}($id);

        $this->assertTrue($result);
        $this->assertFalse($this->modelClass::where('hub_fake_id', $id)->exists());
    }

    private function ucEntity(): string
    {
        return ucfirst($this->entity);
    }

    private function ucEntityPlural(): string
    {
        return "{$this->ucEntity()}s";
    }
}