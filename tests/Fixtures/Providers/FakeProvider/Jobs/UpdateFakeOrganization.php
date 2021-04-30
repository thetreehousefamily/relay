<?php

namespace TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs;

use TheTreehouse\Relay\Support\Contracts\RelayJobContract;

class UpdateFakeOrganization implements RelayJobContract
{
    public function handle()
    {
        return;
    }
}