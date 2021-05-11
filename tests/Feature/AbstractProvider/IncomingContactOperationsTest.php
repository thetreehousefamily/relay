<?php

namespace TheTreehouse\Relay\Tests\Feature\AbstractProvider;

use TheTreehouse\Relay\Tests\Fixtures\Models\Contact;

/** @group feature.provider.incomingOperations */
class IncomingContactOperationsTest extends BaseIncomingOperationsTest
{
    protected $entity = 'contact';

    protected $modelClass = Contact::class;

    protected $incomingProperties = [
        'firstName' => 'Josephine',
        'lastName' => 'Smith',
    ];

    protected $expectedModelProperties = [
        'first_name' => 'Josephine',
        'last_name' => 'Smith',
    ];
}
