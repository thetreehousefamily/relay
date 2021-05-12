<?php

namespace TheTreehouse\Relay\Facades;

use Illuminate\Support\Facades\Facade;
use TheTreehouse\Relay\Support\Contracts\RelayContract;
use TheTreehouse\Relay\Support\FakeRelay;

/**
 * @see \TheTreehouse\Relay\Relay
 *
 * @method static \TheTreehouse\Relay\Relay registerProvider(string $class) Register a provider by its class name
 * @method static string[] getRegisteredProviders() Return the registered providers
 * @method static \TheTreehouse\Relay\Relay useContactModel(string $class) Use the given contact model
 * @method static \TheTreehouse\Relay\Relay useOrganizationModel(string $class) Use the given organization model
 * @method static string|null contactModel() Return the configured contact model class name, or null if it does not exist or is not supported by the application
 * @method static string|null organizationModel() Return the configured organization model class name, or null if it does not exist or is not supported by the application
 * @method static bool supportsContacts() Return a boolean value, indicating whether the application supports the contacts concept, depending on its configuration.
 * @method static bool supportsOrganizations() Return a boolean value, indicating whether the application supports the organizations concept, depending on its configuration.
 * @method static mixed sync(\Closure $callback) Process relay operations synchronously for the duration of $callback execution
 */
class Relay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return RelayContract::class;
    }

    /**
     * Swap the Relay instance to a fake
     *
     * @return \TheTreehouse\Relay\Support\FakeRelay
     */
    public static function fake()
    {
        $bound = static::getFacadeRoot();

        if ($bound instanceof FakeRelay) {
            return $bound;
        }

        static::swap($fake = new FakeRelay($bound));

        return $fake;
    }
}
