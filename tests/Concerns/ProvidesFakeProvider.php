<?php

namespace TheTreehouse\Relay\Tests\Concerns;

use Illuminate\Contracts\Foundation\Application;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider;

trait ProvidesFakeProvider
{
    /**
     * Register the fake provider singleton
     */
    protected function registerFakeProvider(Application $app): self
    {
        $app->singleton(FakeProvider::class);

        return $this;
    }

    /**
     * Return the Fake Provider singleton
     * 
     * @return FakeProvider 
     */
    protected function fakeProvider(): FakeProvider
    {
        return $this->app->make(FakeProvider::class);
    }
}