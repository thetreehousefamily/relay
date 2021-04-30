<?php

namespace TheTreehouse\Relay\Tests\Feature\Dispatcher;

use TheTreehouse\Relay\Tests\Fixtures\Models\Organization;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs\CreateFakeOrganization;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs\DeleteFakeOrganization;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs\UpdateFakeOrganization;

/** @group feature.dispatcher */
class OrganizationDispatcherTest extends BaseDispatcherTest
{
    protected $entityName = 'Organization';

    protected $entityModelClass = Organization::class;

    protected $createJob = CreateFakeOrganization::class;

    protected $updateJob = UpdateFakeOrganization::class;

    protected $deleteJob = DeleteFakeOrganization::class;
}
