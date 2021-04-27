<?php

namespace TheTreehouse\Relay;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use TheTreehouse\Relay\Commands\RelayCommand;

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
}
