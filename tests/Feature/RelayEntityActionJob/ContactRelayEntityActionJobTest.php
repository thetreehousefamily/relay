<?php

namespace TheTreehouse\Relay\Tests\Feature\RelayEntityActionJob;

use TheTreehouse\Relay\Tests\Fixtures\Models\Contact;

/** @group feature.jobs.relayEntityAction */
class ContactRelayEntityActionJobTest extends BaseRelayEntityActionJobTest
{
    protected $entity = 'contact';

    protected $entityModelClass = Contact::class;

    protected $originalEntityProperties = [
        'first_name' => 'Josephine',
        'last_name' => 'Smith',
        'email' => 'example@example.com',
    ];

    protected $outboundEntityProperties = [
        'firstName' => 'Josephine',
        'lastName' => 'Smith',
        'email' => 'example@example.com',
    ];
}
