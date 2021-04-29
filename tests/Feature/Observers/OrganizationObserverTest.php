<?php

namespace TheTreehouse\Relay\Tests\Feature\Observers;

use TheTreehouse\Relay\Observers\OrganizationObserver;
use TheTreehouse\Relay\Tests\Fixtures\Models\Organization;

class OrganizationObserverTest extends BaseObserverTest
{
    protected $entityName = 'Organization';

    protected $observableModel = Organization::class;

    protected $observer = OrganizationObserver::class;
}
