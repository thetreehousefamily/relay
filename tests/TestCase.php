<?php

namespace TheTreehouse\Relay\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use TheTreehouse\Relay\Facades\Relay;
use TheTreehouse\Relay\RelayServiceProvider;
use TheTreehouse\Relay\Tests\Fixtures\Models\Contact;
use TheTreehouse\Relay\Tests\Fixtures\Models\Organization;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Spatie\\Relay\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        $this->configureRelay();

        return [
            RelayServiceProvider::class,
        ];
    }

    protected function configureRelay()
    {
        config(['relay.contact' => Contact::class]);
        config(['relay.organization' => Organization::class]);
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        include_once __DIR__.'/../database/migrations/create_relay_table.php.stub';
        (new \CreatePackageTable())->up();
        */
    }
}
