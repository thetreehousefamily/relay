<?php

namespace TheTreehouse\Relay;

use TheTreehouse\Relay\Exceptions\InvalidProviderException;

class Relay
{
    /**
     * The static list of registered providers
     * 
     * @var string[] $providers
     */
    protected static array $providers = [];

    /**
     * Register a provider by its class name
     * 
     * @param string $class
     * @return void
     */
    public static function registerProvider(string $class) : void
    {
        if (!class_exists($class) || !is_subclass_of($class, AbstractProvider::class)) {
            throw InvalidProviderException::fromInvalidClass($class);
        }

        self::$providers[] = $class;
    }

    /**
     * Return the registered providers
     * 
     * @return string[]
     */
    public static function getRegisteredProviders() : array
    {
        return self::$providers;
    }
}
