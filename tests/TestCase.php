<?php

namespace TheTreehouse\Relay\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Bus;
use Orchestra\Testbench\TestCase as Orchestra;
use TheTreehouse\Relay\Facades\Relay;
use TheTreehouse\Relay\RelayServiceProvider;
use TheTreehouse\Relay\Tests\Concerns\AssertsAgainstRelayEntityActionJob;
use TheTreehouse\Relay\Tests\Concerns\ProvidesFakeProvider;
use TheTreehouse\Relay\Tests\Contracts\UsingFakeRelay;
use TheTreehouse\Relay\Tests\Fixtures\Models\Contact;
use TheTreehouse\Relay\Tests\Fixtures\Models\Organization;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider;

class TestCase extends Orchestra
{
    use AssertsAgainstRelayEntityActionJob;
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

        Bus::fake();

        if ($this instanceof UsingFakeRelay) {
            Relay::fake();
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

        config(['relay.providers.fake_provider.contact_fields' => [
            'first_name' => 'firstName',
            'last_name' => 'lastName',
            'email' => 'email',
        ]]);

        config(['relay.providers.fake_provider.organization_fields' => [
            'name' => 'companyName',
        ]]);
    }

    protected function relayProviders(): array
    {
        return [
            FakeProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        $this->registerFakeProvider($app);

        $this->runFixtureMigrations();

        /*
        include_once __DIR__.'/../database/migrations/create_relay_table.php.stub';
        (new \CreatePackageTable())->up();
        */
    }

    protected function runFixtureMigrations()
    {
        $migrations = [
            'CreateContactsTable' => '/Fixtures/Migrations/create_contacts_table.php',
            'CreateOrganizationsTable' => '/Fixtures/Migrations/create_organizations_table.php',
        ];

        foreach ($migrations as $class => $file) {
            include_once __DIR__.$file;
            (new $class)->up();
        }
    }
}
