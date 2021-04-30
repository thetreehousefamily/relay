<?php

namespace TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs;

use TheTreehouse\Relay\Support\Contracts\RelayJobContract;

class CreateFakeContact implements RelayJobContract
{
    public function handle()
    {
        return;
    }
}
