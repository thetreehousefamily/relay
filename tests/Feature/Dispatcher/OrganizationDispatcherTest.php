<?php

namespace TheTreehouse\Relay\Tests\Feature\Dispatcher;

use TheTreehouse\Relay\Tests\Fixtures\Models\Organization;

class OrganizationDispatcherTest extends BaseDispatcherTest
{
    protected $entityName = 'Organization';

    protected $entityModelClass = Organization::class;
}