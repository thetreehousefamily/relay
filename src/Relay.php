<?php

namespace TheTreehouse\Relay;

use Illuminate\Database\Eloquent\Model;
use TheTreehouse\Relay\Exceptions\InvalidModelException;
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
     * The contact model class
     * 
     * @var string|null $contactModel
     */
    protected static $contactModel;

    /**
     * The organization model class
     * 
     * @var string|null $organizationModel
     */
    protected static $organizationModel;

    /**
     * Register a provider by its class name
     * 
     * @param string $class
     * @return void
     */
    public static function registerProvider(string $class) : void
    {
        if (!self::classExtendsParent($class, AbstractProvider::class)) {
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

    /**
     * Use the given contact model
     * 
     * @param string $class
     * @return void
     */
    public static function useContactModel(string $class) : void
    {
        if (!self::classExtendsParent($class, Model::class)) {
            throw InvalidModelException::fromInvalidClass($class);
        }

        self::$contactModel = $class;
    }

    /**
     * Use the given organization model
     * 
     * @param string $class
     * @return void
     */
    public static function useOrganizationModel(string $class): void
    {
        if (!self::classExtendsParent($class, Model::class)) {
            throw InvalidModelException::fromInvalidClass($class);
        }

        self::$organizationModel = $class;
    }

    /**
     * Return the configured contact model class name, or null if it does not exist or
     * is not supported by the application
     * 
     * @return null|string
     */
    public static function contactModel() : ? string
    {
        return self::$contactModel;
    }

    /**
     * Return the configured organization model class name, or null if it does not exist
     * or is not supported by the application
     * 
     * @return null|string
     */
    public static function organizationModel() : ? string
    {
        return self::$organizationModel;
    }

    /**
     * Ensure that the provided class name exists, and is a subclass of the provided
     * parent class
     * 
     * @param string $class
     * @param string $parent
     * @return bool
     */
    private static function classExtendsParent(string $class, string $parent) : bool
    {
        return class_exists($class) && is_subclass_of($class, $parent);
    }
}
