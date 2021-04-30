<?php

namespace TheTreehouse\Relay\Tests\Feature\Dispatcher;

use TheTreehouse\Relay\Tests\Fixtures\Models\Contact;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs\CreateFakeContact;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs\DeleteFakeContact;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs\UpdateFakeContact;

/** @group feature.dispatcher */
class ContactDispatcherTest extends BaseDispatcherTest
{
    protected $entityName = 'Contact';

    protected $entityModelClass = Contact::class;

    protected $createJob = CreateFakeContact::class;

    protected $updateJob = UpdateFakeContact::class;

    protected $deleteJob = DeleteFakeContact::class;
}