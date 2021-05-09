<?php

namespace TheTreehouse\Relay\Tests\Feature\Dispatcher;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;
use TheTreehouse\Relay\Dispatcher;
use TheTreehouse\Relay\Facades\Relay;
use TheTreehouse\Relay\Jobs\RelayEntityAction;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\FakeProvider;
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

    /**
     * The create job class for the entity, instantiated by Fake Provider
     *
     * @var string
     */
    protected $createJob;

    /**
     * The update job class for the entity, instantiated by Fake Provider
     *
     * @var string
     */
    protected $updateJob;

    /**
     * The delete job class for the entity, instantiated by Fake Provider
     *
     * @var string
     */
    protected $deleteJob;

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

        $this->assertRelayEntityActionDispatched($model, 'create');
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

    public function test_it_dispatches_create_job_on_relay_update_if_entity_does_not_yet_exist()
    {
        $model = new $this->entityModelClass;

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayUpdated{$this->entityName}"}($model);

        $this->assertRelayEntityActionDispatched($model, 'create');
    }

    public function test_it_dispatches_update_job_from_provider()
    {
        $model = new $this->entityModelClass([
            'fake_provider_id' => Str::random(),
        ]);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayUpdated{$this->entityName}"}($model);

        $this->assertRelayEntityActionDispatched($model, 'update');
    }

    public function test_it_does_not_relay_deleted_if_entity_not_supported_by_application()
    {
        Relay::fake()->{"setSupports{$this->entityName}s"}(false);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayDeleted{$this->entityName}"}(new $this->entityModelClass);

        $this->assertRelayEntityActionNotDispatched();
    }

    public function test_it_does_not_relay_deleted_if_entity_not_supported_by_provider()
    {
        $this->fakeProvider()->{"setSupports{$this->entityName}s"}(false);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayDeleted{$this->entityName}"}(new $this->entityModelClass);

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

        $this->assertRelayEntityActionDispatched($model, 'delete');
    }

    private function newDispatcher(): Dispatcher
    {
        return $this->app->make(Dispatcher::class);
    }

    private function assertRelayEntityActionNotDispatched()
    {
        Bus::assertNotDispatched(RelayEntityAction::class);
    }

    private function assertRelayEntityActionDispatched(Model $entity, string $action)
    {
        Bus::assertDispatched(RelayEntityAction::class, function (RelayEntityAction $job) use ($entity, $action) {
            if (
                $job->entity !== $entity
                || $job->entityType !== strtolower($this->entityName)
                || $job->action !== $action
                || $job->provider !== FakeProvider::class
            ) {
                $this->fail('Relay Entity Action job was misconfigured');
                return false;
            }

            return true;
        });
    }
}
