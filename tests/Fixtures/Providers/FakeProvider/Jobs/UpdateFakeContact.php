<?php

namespace TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs;

use TheTreehouse\Relay\Support\Contracts\RelayJobContract;

class UpdateFakeContact implements RelayJobContract
{
    public function handle()
    {
        return;
    }
}