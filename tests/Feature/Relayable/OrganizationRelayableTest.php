<?php

namespace TheTreehouse\Relay\Tests\Feature\Relayable;

use TheTreehouse\Relay\Tests\Fixtures\Models\Organization;

/** @group feature.relayable */
class OrganizationRelayableTest extends BaseRelayableTest
{
    protected $entity = 'organization';

    protected $modelClass = Organization::class;
}
