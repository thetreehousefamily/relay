<?php

namespace TheTreehouse\Relay\Tests;

use Illuminate\Contracts\Foundation\Application;

class ExampleFeatureTest extends TestCase
{
    public function test_app_is_resolved()
    {
        $this->assertInstanceOf(Application::class, app());
    }
}
