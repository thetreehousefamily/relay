<?php

namespace TheTreehouse\Relay\Tests\Feature\Dispatcher;

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

    public function test_it_does_not_relay_created_if_entity_not_supported_by_application()
    {
        Relay::fake()->{"setSupports{$this->entityName}s"}(false);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayCreated{$this->entityName}"}(new $this->entityModelClass);

        $this->fakeProvider()->{"assertNo{$this->entityName}sCreated"}();
    }

    public function test_it_does_not_relay_created_if_entity_not_supported_by_provider()
    {
        $this->fakeProvider()->{"setSupports{$this->entityName}s"}(false);

        $dispatcher = $this->newDispatcher();

        $dispatcher->{"relayCreated{$this->entityName}"}(new $this->entityModelClass);

        $this->fakeProvider()->{"assertNo{$this->entityName}sCreated"}();
    }

    // public function test_it_does_not_relay_created_if_entity_already_exists()
    // {
    //     $dispatcher = $this->newDispatcher();

    //     $dispatcher->relayCreatedContact(new Contact());

    //     $this->fakeProvider()->assertNoContactsCreated();
    // }

    // public function test_it_dispatches_create_job_from_provider()
    // {
    //     $this->assertTrue(true);
    // }

    // public function test_it_does_not_relay_updated_if_entity_not_supported()
    // {
    //     $this->assertTrue(true);
    // }

    // public function test_it_dispatches_create_job_on_relay_update_if_entity_does_not_yet_exist()
    // {
    //     $this->assertTrue(true);
    // }

    // public function test_it_dispatches_update_job_from_provider()
    // {
    //     $this->assertTrue(true);
    // }

    // TODO: Delete Tests

    private function newDispatcher(): Dispatcher
    {
        return $this->app->make(Dispatcher::class);
    }
}