<?php

namespace TheTreehouse\Relay\Tests\Feature\Dispatcher;

use TheTreehouse\Relay\Tests\Fixtures\Models\Contact;

/** @group feature.dispatcher */
class ContactDispatcherTest extends BaseDispatcherTest
{
    protected $entityName = 'Contact';

    protected $entityModelClass = Contact::class;
}
