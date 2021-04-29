<?php

namespace TheTreehouse\Relay\Tests\Feature;

use TheTreehouse\Relay\Tests\TestCase;

class DispatcherTest extends TestCase
{
    public function test_it_does_not_relay_created_if_entity_not_supported()
    {
        $this->fakeProvider()->assertNoContactsCreated();
    }

    public function test_it_does_not_relay_created_if_entity_already_exists()
    {
        // ...
    }

    public function test_it_dispatches_create_job_from_provider()
    {
        // ...
    }

    public function test_it_does_not_relay_updated_if_entity_not_supported()
    {
        // ...
    }

    public function test_it_dispatches_create_job_on_relay_update_if_entity_does_not_yet_exist()
    {
        // ...
    }

    public function test_it_dispatches_update_job_from_provider()
    {
        // ...
    }

    // TODO: Delete Tests
}