<?php

namespace TheTreehouse\Relay\Tests\Feature\RelayEntityActionJob;

use TheTreehouse\Relay\Tests\Fixtures\Models\Organization;

/** @group feature.jobs.relayEntityAction */
class OrganizationRelayEntityActionJobTest extends BaseRelayEntityActionJobTest
{
    protected $entity = 'organization';

    protected $entityModelClass = Organization::class;

    protected $originalEntityProperties = [
        'name' => 'Example Company',
    ];

    protected $outboundEntityProperties = [
        'companyName' => 'Example Company',
    ];
}
