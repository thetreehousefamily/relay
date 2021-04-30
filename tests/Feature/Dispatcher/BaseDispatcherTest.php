<?php

namespace TheTreehouse\Relay\Tests\Feature\Dispatcher;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;
use TheTreehouse\Relay\Dispatcher;
use TheTreehouse\Relay\Facades\Relay;
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

        $this->assertNoEntitiesCreated();
    }

    public function test_it_does_not_relay_created_if_entity_not_supported_by_provider()
    {
        $this->fakeProvider()->{"setSupports{$this->entityName}s"}(false);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayCreated{$this->entityName}"}(new $this->entityModelClass);

        $this->assertNoEntitiesCreated();
    }

    public function test_it_does_not_relay_created_if_entity_already_exists()
    {
        $model = new $this->entityModelClass([
            'fake_provider_id' => Str::random()
        ]);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayCreated{$this->entityName}"}($model);

        $this->assertNoEntitiesCreated();
    }

    public function test_it_dispatches_create_job_from_provider()
    {
        $model = new $this->entityModelClass;

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayCreated{$this->entityName}"}($model);

        $this->assertEntityCreated($model);
    }

    public function test_it_does_not_relay_updated_if_entity_not_supported_by_application()
    {
        Relay::fake()->{"setSupports{$this->entityName}s"}(false);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayUpdated{$this->entityName}"}(new $this->entityModelClass);

        $this->assertNoEntitiesUpdated();
    }

    public function test_it_does_not_relay_updated_if_entity_not_supported_by_provider()
    {
        $this->fakeProvider()->{"setSupports{$this->entityName}s"}(false);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayUpdated{$this->entityName}"}(new $this->entityModelClass);

        $this->assertNoEntitiesUpdated();
    }

    public function test_it_dispatches_create_job_on_relay_update_if_entity_does_not_yet_exist()
    {
        $model = new $this->entityModelClass;

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayUpdated{$this->entityName}"}($model);

        $this->assertEntityCreated($model);
    }

    public function test_it_dispatches_update_job_from_provider()
    {
        $model = new $this->entityModelClass([
            'fake_provider_id' => Str::random()
        ]);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayUpdated{$this->entityName}"}($model);

        $this->assertEntityUpdated($model);
    }

    public function test_it_does_not_relay_deleted_if_entity_not_supported_by_application()
    {
        Relay::fake()->{"setSupports{$this->entityName}s"}(false);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayDeleted{$this->entityName}"}(new $this->entityModelClass);

        $this->assertNoEntitiesDeleted();
    }

    public function test_it_does_not_relay_deleted_if_entity_not_supported_by_provider()
    {
        $this->fakeProvider()->{"setSupports{$this->entityName}s"}(false);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayDeleted{$this->entityName}"}(new $this->entityModelClass);

        $this->assertNoEntitiesDeleted();
    }

    public function test_it_does_not_relay_deleted_if_entity_does_not_exist()
    {
        $model = new $this->entityModelClass;

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayDeleted{$this->entityName}"}($model);

        $this->assertNoEntitiesDeleted();
    }

    public function test_it_dispatches_delete_job_from_provider()
    {
        $model = new $this->entityModelClass([
            'fake_provider_id' => Str::random()
        ]);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayDeleted{$this->entityName}"}($model);

        $this->assertEntityDeleted($model);
    }

    private function newDispatcher(): Dispatcher
    {
        return $this->app->make(Dispatcher::class);
    }

    private function assertNoEntitiesCreated()
    {
        $this->fakeProvider()->{"assertNo{$this->entityName}sCreated"}();

        Bus::assertNotDispatched($this->createJob);

        return $this;
    }

    private function assertEntityCreated($entity = null)
    {
        $this->fakeProvider()->{"assert{$this->entityName}Created"}($entity);

        Bus::assertDispatched($this->createJob);

        return $this;
    }

    private function assertNoEntitiesUpdated()
    {
        $this->fakeProvider()->{"assertNo{$this->entityName}sUpdated"}();

        Bus::assertNotDispatched($this->updateJob);

        return $this;
    }

    private function assertEntityUpdated($entity = null)
    {
        $this->fakeProvider()->{"assert{$this->entityName}Updated"}($entity);

        Bus::assertDispatched($this->updateJob);

        return $this;
    }

    private function assertNoEntitiesDeleted()
    {
        $this->fakeProvider()->{"assertNo{$this->entityName}sDeleted"}();

        Bus::assertNotDispatched($this->deleteJob);

        return $this;
    }

    private function assertEntityDeleted($entity = null)
    {
        $this->fakeProvider()->{"assert{$this->entityName}Deleted"}($entity);

        Bus::assertDispatched($this->deleteJob);

        return $this;
    }
}