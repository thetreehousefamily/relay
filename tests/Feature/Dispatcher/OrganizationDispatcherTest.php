<?php

namespace TheTreehouse\Relay\Tests\Feature\Dispatcher;

use TheTreehouse\Relay\Tests\Fixtures\Models\Organization;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs\CreateFakeOrganization;

/** @group feature.dispatcher */
class OrganizationDispatcherTest extends BaseDispatcherTest
{
    protected $entityName = 'Organization';

    protected $entityModelClass = Organization::class;

    protected $createJob = CreateFakeOrganization::class;
}
