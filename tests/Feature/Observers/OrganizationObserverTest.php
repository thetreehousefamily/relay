<?php

namespace TheTreehouse\Relay\Tests\Feature\Observers;

use TheTreehouse\Relay\Observers\OrganizationObserver;
use TheTreehouse\Relay\Tests\Concerns\Observers\TestsObserverFunctionality;
use TheTreehouse\Relay\Tests\Fixtures\Models\Organization;
use TheTreehouse\Relay\Tests\TestCase;

class OrganizationObserverTest extends TestCase
{
    use TestsObserverFunctionality;

    public function test_it_observes_model_events()
    {
        $dispatcher = $this->getDispatcher();

        $this->assertListenersIncludeClassListener(
            $dispatcher->getListeners("eloquent.created: " . Organization::class),
            OrganizationObserver::class . "@created"
        );

        $this->assertListenersIncludeClassListener(
            $dispatcher->getListeners("eloquent.updated: " . Organization::class),
            OrganizationObserver::class . "@updated"
        );

        $this->assertListenersIncludeClassListener(
            $dispatcher->getListeners("eloquent.deleted: " . Organization::class),
            OrganizationObserver::class . "@deleted"
        );
    }
}