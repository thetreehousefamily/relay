<?php

namespace TheTreehouse\Relay\Tests\Feature\Observers;

use TheTreehouse\Relay\Observers\ContactObserver;
use TheTreehouse\Relay\Tests\Concerns\Observers\TestsObserverFunctionality;
use TheTreehouse\Relay\Tests\Fixtures\Models\Contact;
use TheTreehouse\Relay\Tests\TestCase;

class ContactObserverTest extends TestCase
{
    use TestsObserverFunctionality;

    public function test_it_observes_model_events()
    {
        $dispatcher = $this->getDispatcher();

        $this->assertListenersIncludeClassListener(
            $dispatcher->getListeners("eloquent.created: " . Contact::class),
            ContactObserver::class . "@created"
        );

        $this->assertListenersIncludeClassListener(
            $dispatcher->getListeners("eloquent.updated: " . Contact::class),
            ContactObserver::class . "@updated"
        );

        $this->assertListenersIncludeClassListener(
            $dispatcher->getListeners("eloquent.deleted: " . Contact::class),
            ContactObserver::class . "@deleted"
        );
    }
}
