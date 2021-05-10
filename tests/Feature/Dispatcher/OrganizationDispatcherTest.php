<?php

namespace TheTreehouse\Relay\Tests\Feature\Dispatcher;

use TheTreehouse\Relay\Tests\Fixtures\Models\Organization;

/** @group feature.dispatcher */
class OrganizationDispatcherTest extends BaseDispatcherTest
{
    protected $entityName = 'Organization';

    protected $entityModelClass = Organization::class;
}
