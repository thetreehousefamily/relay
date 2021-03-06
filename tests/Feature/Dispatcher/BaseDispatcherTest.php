<?php

namespace TheTreehouse\Relay\Tests\Feature\Dispatcher;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;
use TheTreehouse\Relay\Dispatcher;
use TheTreehouse\Relay\Facades\Relay;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider;
use TheTreehouse\Relay\Tests\TestCase;

abstract class BaseDispatcherTest extends TestCase
{
    /**
     * The name of the entity, either Contact or Organization
     *
     * @var string
     */
    protected $entityName;

    /**
     * The model class of the entity, either Contact::class or Organization::class
     *
     * @var string
     */
    protected $entityModelClass;

    public function test_it_does_not_relay_created_if_entity_not_supported_by_application()
    {
        Relay::fake()->{"setSupports{$this->entityName}s"}(false);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayCreated{$this->entityName}"}(new $this->entityModelClass);

        $this->assertRelayEntityActionNotDispatched();
    }

    public function test_it_does_not_relay_created_if_entity_not_supported_by_provider()
    {
        $this->fakeProvider()->{"setSupports{$this->entityName}s"}(false);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayCreated{$this->entityName}"}(new $this->entityModelClass);

        $this->assertRelayEntityActionNotDispatched();
    }

    public function test_it_does_not_relay_created_if_automatic_relay_disabled_for_provider()
    {
        config()->set('relay.providers.fake_provider.auto', false);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayCreated{$this->entityName}"}(new $this->entityModelClass);

        $this->assertRelayEntityActionNotDispatched();
    }

    public function test_it_does_not_relay_created_if_entity_already_exists()
    {
        $model = new $this->entityModelClass([
            'fake_provider_id' => Str::random(),
        ]);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayCreated{$this->entityName}"}($model);

        $this->assertRelayEntityActionNotDispatched();
    }

    public function test_it_dispatches_create_job_from_provider()
    {
        $model = new $this->entityModelClass;

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayCreated{$this->entityName}"}($model);

        $this->assertRelayEntityActionDispatched($model, $this->entityName, 'create', FakeProvider::class);
    }

    public function test_it_does_not_relay_updated_if_entity_not_supported_by_application()
    {
        Relay::fake()->{"setSupports{$this->entityName}s"}(false);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayUpdated{$this->entityName}"}(new $this->entityModelClass);

        $this->assertRelayEntityActionNotDispatched();
    }

    public function test_it_does_not_relay_updated_if_entity_not_supported_by_provider()
    {
        $this->fakeProvider()->{"setSupports{$this->entityName}s"}(false);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayUpdated{$this->entityName}"}(new $this->entityModelClass);

        $this->assertRelayEntityActionNotDispatched();
    }

    public function test_it_does_not_relay_updated_if_automatic_relay_disabled_for_provider()
    {
        config()->set('relay.providers.fake_provider.auto', false);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayUpdated{$this->entityName}"}(new $this->entityModelClass);

        $this->assertRelayEntityActionNotDispatched();
    }

    public function test_it_dispatches_create_job_on_relay_update_if_entity_does_not_yet_exist()
    {
        $model = new $this->entityModelClass;

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayUpdated{$this->entityName}"}($model);

        $this->assertRelayEntityActionDispatched($model, $this->entityName, 'create', FakeProvider::class);
    }

    public function test_it_dispatches_update_job_from_provider()
    {
        $model = new $this->entityModelClass([
            'fake_provider_id' => Str::random(),
        ]);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayUpdated{$this->entityName}"}($model);

        $this->assertRelayEntityActionDispatched($model, $this->entityName, 'update', FakeProvider::class);
    }

    public function test_it_does_not_relay_deleted_if_entity_not_supported_by_application()
    {
        Relay::fake()->{"setSupports{$this->entityName}s"}(false);

        $model = new $this->entityModelClass([
            'fake_provider_id' => Str::random(),
        ]);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayDeleted{$this->entityName}"}($model);

        $this->assertRelayEntityActionNotDispatched();
    }

    public function test_it_does_not_relay_deleted_if_entity_not_supported_by_provider()
    {
        $this->fakeProvider()->{"setSupports{$this->entityName}s"}(false);

        $model = new $this->entityModelClass([
            'fake_provider_id' => Str::random(),
        ]);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayDeleted{$this->entityName}"}($model);

        $this->assertRelayEntityActionNotDispatched();
    }

    public function test_it_does_not_relay_deleted_if_automatic_relay_disabled_for_provider()
    {
        config()->set('relay.providers.fake_provider.auto', false);

        $model = new $this->entityModelClass([
            'fake_provider_id' => Str::random(),
        ]);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayDeleted{$this->entityName}"}($model);

        $this->assertRelayEntityActionNotDispatched();
    }

    public function test_it_does_not_relay_deleted_if_entity_does_not_exist()
    {
        $model = new $this->entityModelClass;

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayDeleted{$this->entityName}"}($model);

        $this->assertRelayEntityActionNotDispatched();
    }

    public function test_it_dispatches_delete_job_from_provider()
    {
        $model = new $this->entityModelClass([
            'fake_provider_id' => Str::random(),
        ]);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayDeleted{$this->entityName}"}($model);

        $this->assertRelayEntityActionDispatched($model, $this->entityName, 'delete', FakeProvider::class);
    }

    public function test_it_dispatches_synchronously()
    {
        Bus::spy()->expects('dispatchSync');

        $model = new $this->entityModelClass;

        $result = Relay::sync(function () use ($model) {
            $dispatcher = $this->newDispatcher();
    
            $dispatcher->{"relayCreated{$this->entityName}"}($model);

            return $model;
        });

        $this->assertSame($model, $result);
    }

    public function test_it_dispatches_create_on_manual_push()
    {
        $model = new $this->entityModelClass;

        $dispatcher = $this->newDispatcher();

        $dispatcher->processManualRelay($model);

        $this->assertRelayEntityActionDispatched($model, $this->entityName, 'create', FakeProvider::class);
    }

    public function test_it_dispatches_update_on_manual_push()
    {
        $model = new $this->entityModelClass([
            'fake_provider_id' => Str::random(),
        ]);

        $dispatcher = $this->newDispatcher();

        $dispatcher->processManualRelay($model);

        $this->assertRelayEntityActionDispatched($model, $this->entityName, 'update', FakeProvider::class);
    }

    private function newDispatcher(): Dispatcher
    {
        return $this->app->make(Dispatcher::class);
    }
}
