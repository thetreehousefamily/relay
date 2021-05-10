<?php

namespace TheTreehouse\Relay\Tests\Feature\RelayEntityActionJob;

use TheTreehouse\Relay\Tests\Fixtures\Models\Contact;

/** @group feature.jobs.relayEntityAction */
class ContactRelayEntityActionJobTest extends BaseRelayEntityActionJobTest
{
    protected $entity = 'contact';

    protected $entityModelClass = Contact::class;
}
