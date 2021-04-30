<?php

namespace TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs;

use TheTreehouse\Relay\Support\Contracts\RelayJobContract;

class DeleteFakeContact implements RelayJobContract
{
    public function handle()
    {
        return;
    }
}