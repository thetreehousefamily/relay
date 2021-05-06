<?php

namespace TheTreehouse\Relay\Tests\Feature\AbstractProvider;

use TheTreehouse\Relay\Tests\Fixtures\Models\Contact;

class IncomingContactOperationsTest extends BaseIncomingOperationsTest
{
    protected $entity = 'contact';

    protected $modelClass = Contact::class;
}
