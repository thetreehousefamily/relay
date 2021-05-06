<?php

namespace TheTreehouse\Relay\Tests\Feature\AbstractProvider;

use TheTreehouse\Relay\Tests\Fixtures\Models\Organization;

class IncomingOrganizationOperationsTest extends BaseIncomingOperationsTest
{
    protected $entity = 'organization';

    protected $modelClass = Organization::class;
}
