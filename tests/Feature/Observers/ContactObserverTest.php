<?php

namespace TheTreehouse\Relay\Tests\Feature\Observers;

use TheTreehouse\Relay\Observers\ContactObserver;
use TheTreehouse\Relay\Tests\Fixtures\Models\Contact;

class ContactObserverTest extends BaseObserverTest
{
    protected $entityName = 'Contact';

    protected $observableModel = Contact::class;

    protected $observer = ContactObserver::class;
}
