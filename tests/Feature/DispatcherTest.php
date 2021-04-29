<?php

namespace TheTreehouse\Relay\Tests\Feature;

use TheTreehouse\Relay\Dispatcher;
use TheTreehouse\Relay\Facades\Relay;
use TheTreehouse\Relay\Tests\TestCase;

class DispatcherTest extends TestCase
{
    public function test_it_does_not_relay_created_if_entity_not_supported_by_application()
    {
        Relay::fake()->setSupportsContacts(false);

        $this->fakeProvider()->assertNoContactsCreated();
    }

    // public function test_it_does_not_relay_created_if_entity_already_exists()
    // {
    //     $this->assertTrue(true);
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