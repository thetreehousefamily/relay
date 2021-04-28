<?php

namespace TheTreehouse\Relay;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use TheTreehouse\Relay\Commands\RelayCommand;
use TheTreehouse\Relay\Observers\ContactObserver;
use TheTreehouse\Relay\Observers\OrganizationObserver;

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
        $this->app->singleton(Relay::class);

        $this->app->alias(Relay::class, 'relay');
    }

    public function packageBooted()
    {
        /**
         * Has the effect of initializing the singleton, if not already
         *
         * @var \TheTreehouse\Relay\Relay $relay
         */
        $relay = $this->app->make('relay');

        if (config('relay.contact')) {
            $relay->useContactModel(config('relay.contact'));
            config('relay.contact')::observe(ContactObserver::class);
        }

        if (config('relay.organization')) {
            $relay->useOrganizationModel(config('relay.organization'));
            config('relay.organization')::observe(OrganizationObserver::class);
        }
    }
}
