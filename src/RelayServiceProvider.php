<?php

namespace TheTreehouse\Relay;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use TheTreehouse\Relay\Commands\RelayCommand;
use TheTreehouse\Relay\Mutators\DateMutator;
use TheTreehouse\Relay\Mutators\DateTimeMutator;
use TheTreehouse\Relay\Observers\ContactObserver;
use TheTreehouse\Relay\Observers\OrganizationObserver;
use TheTreehouse\Relay\Support\Contracts\RelayContract;

class RelayServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('relay')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_relay_table')
            ->hasCommand(RelayCommand::class);
    }

    public function packageRegistered()
    {
        $this->app->singleton(RelayContract::class, Relay::class);
        $this->app->alias(RelayContract::class, 'relay');

        $this->app->singleton(Dispatcher::class);
        $this->app->alias(Dispatcher::class, 'relay.dispatcher');
    }

    public function packageBooted()
    {
        /**
         * Has the effect of initializing the singleton, if not already
         *
         * @var \TheTreehouse\Relay\Relay $relay
         */
        $relay = $this->app->make(RelayContract::class);

        if (config('relay.contact')) {
            $relay->useContactModel(config('relay.contact'));

            ((string) $relay->contactModel())::observe(ContactObserver::class);
        }

        if (config('relay.organization')) {
            $relay->useOrganizationModel(config('relay.organization'));

            ((string) $relay->organizationModel())::observe(OrganizationObserver::class);
        }

        $this->registerDefaultMutators($relay);
    }

    private function registerDefaultMutators(RelayContract $relay)
    {
        $relay
            ->registerMutator(DateMutator::class, 'date')
            ->registerMutator(DateTimeMutator::class, 'datetime');
    }
}
