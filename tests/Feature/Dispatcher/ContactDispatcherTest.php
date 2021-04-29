<?php

namespace TheTreehouse\Relay\Tests\Feature\Dispatcher;

use TheTreehouse\Relay\Tests\Fixtures\Models\Contact;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs\CreateFakeContact;

/** @group feature.dispatcher */
class ContactDispatcherTest extends BaseDispatcherTest
{
    protected $entityName = 'Contact';

    protected $entityModelClass = Contact::class;

    protected $createJob = CreateFakeContact::class;
}
