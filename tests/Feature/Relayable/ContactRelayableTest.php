<?php

namespace TheTreehouse\Relay\Tests\Feature\Relayable;

use TheTreehouse\Relay\Tests\Fixtures\Models\Contact;

/** @group feature.relayable */
class ContactRelayableTest extends BaseRelayableTest
{
    protected $entity = 'contact';

    protected $modelClass = Contact::class;
}
