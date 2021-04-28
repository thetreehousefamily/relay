<?php

namespace TheTreehouse\Relay;

use Illuminate\Database\Eloquent\Model;
use TheTreehouse\Relay\Exceptions\InvalidModelException;
use TheTreehouse\Relay\Exceptions\InvalidProviderException;

class Relay
{
    /**
     * The list of registered providers
     * 
     * @var string[] $providers
     */
    protected array $providers = [];

    /**
     * The contact model class
     * 
     * @var string|null $contactModel
     */
    protected $contactModel;

    /**
     * The organization model class
     * 
     * @var string|null $organizationModel
     */
    protected $organizationModel;

    /**
     * Register a provider by its class name
     * 
     * @param string $class
     * @return static
     */
    public function registerProvider(string $class): Relay
    {
        if (!$this->classExtendsParent($class, AbstractProvider::class)) {
            throw InvalidProviderException::fromInvalidClass($class);
        }

        $this->providers[] = $class;

        return $this;
    }

    /**
     * Return the registered providers
     * 
     * @return string[]
     */
    public function getRegisteredProviders(): array
    {
        return $this->providers;
    }

    /**
     * Use the given contact model
     * 
     * @param string $class
     * @return static
     */
    public function useContactModel(string $class): Relay
    {
        if (!$this->classExtendsParent($class, Model::class)) {
            throw InvalidModelException::fromInvalidClass($class);
        }

        $this->contactModel = $class;

        return $this;
    }

    /**
     * Use the given organization model
     * 
     * @param string $class
     * @return static
     */
    public function useOrganizationModel(string $class): Relay
    {
        if (!$this->classExtendsParent($class, Model::class)) {
            throw InvalidModelException::fromInvalidClass($class);
        }

        $this->organizationModel = $class;

        return $this;
    }

    /**
     * Return the configured contact model class name, or null if it does not exist or
     * is not supported by the application
     * 
     * @return string|null
     */
    public function contactModel(): ? string
    {
        return $this->contactModel;
    }

    /**
     * Return the configured organization model class name, or null if it does not exist
     * or is not supported by the application
     * 
     * @return string|null
     */
    public function organizationModel(): ? string
    {
        return $this->organizationModel;
    }

    /**
     * Return a boolean value, indicating whether the application supports the contacts
     * concept, depending on its configuration.
     * 
     * @return bool
     */
    public function supportsContacts(): bool
    {
        return (bool) $this->contactModel();
    }

    /**
     * Return a boolean value, indicating whether the application supports the organizations
     * concept, depending on its configuration.
     * 
     * @return bool
     */
    public function supportsOrganizations(): bool
    {
        return (bool) $this->organizationModel();
    }

    /**
     * Ensure that the provided class name exists, and is a subclass of the provided
     * parent class
     * 
     * @param string $class
     * @param string $parent
     * @return bool
     */
    private function classExtendsParent(string $class, string $parent) : bool
    {
        return class_exists($class) && is_subclass_of($class, $parent);
    }
}
