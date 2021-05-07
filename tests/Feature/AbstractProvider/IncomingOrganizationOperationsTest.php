<?php

namespace TheTreehouse\Relay\Tests\Feature\AbstractProvider;

use TheTreehouse\Relay\Tests\Fixtures\Models\Organization;

/** @group feature.provider.incomingOperations */
class IncomingOrganizationOperationsTest extends BaseIncomingOperationsTest
{
    protected $entity = 'organization';

    protected $modelClass = Organization::class;
}
