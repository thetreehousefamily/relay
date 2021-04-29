<?php

namespace TheTreehouse\Relay\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use TheTreehouse\Relay\Facades\Relay;
use TheTreehouse\Relay\RelayServiceProvider;
use TheTreehouse\Relay\Tests\Concerns\ProvidesFakeProvider;
use TheTreehouse\Relay\Tests\Fixtures\Models\Contact;
use TheTreehouse\Relay\Tests\Fixtures\Models\Organization;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider;

class TestCase extends Orchestra
{
    use ProvidesFakeProvider;

    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Spatie\\Relay\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        foreach ($this->relayProviders() as $provider) {
            Relay::registerProvider($provider);
        }
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

    protected function relayProviders(): array
    {
        return [
            FakeProvider::class
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        $this->registerFakeProvider($app);

        /*
        include_once __DIR__.'/../database/migrations/create_relay_table.php.stub';
        (new \CreatePackageTable())->up();
        */
    }
}
