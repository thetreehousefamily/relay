<?php

namespace TheTreehouse\Relay;

use Illuminate\Database\Eloquent\Model;
use TheTreehouse\Relay\Exceptions\InvalidModelException;
use TheTreehouse\Relay\Exceptions\InvalidProviderException;
use TheTreehouse\Relay\Exceptions\PropertyException;
use TheTreehouse\Relay\Support\Contracts\MutatorContract;
use TheTreehouse\Relay\Support\Contracts\RelayContract;

class Relay implements RelayContract
{
    /**
     * The list of registered providers
     *
     * @var string[]
     */
    protected array $providers = [];

    /**
     * The array of registered mutators (by class name)
     *
     * @var string[]
     */
    protected $mutators = [];

    /**
     * The array of mutator aliases
     *
     * @var string[]
     */
    protected $mutatorAliases = [];

    /**
     * The contact model class
     *
     * @var string|null
     */
    protected $contactModel;

    /**
     * The organization model class
     *
     * @var string|null
     */
    protected $organizationModel;

    /**
     * Register a provider by its class name
     *
     * @param string $class
     * @return self
     */
    public function registerProvider(string $class): self
    {
        if (! $this->classExtendsParent($class, AbstractProvider::class)) {
            throw InvalidProviderException::fromInvalidClass($class);
        }

        if (in_array($class, $this->providers)) {
            return $this;
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
     * Shortcut for mapping the registered provider classes to resolved instances
     *
     * @return \TheTreehouse\Relay\AbstractProvider[]
     */
    public function getProviders(): array
    {
        $arr = [];

        foreach ($this->getRegisteredProviders() as $providerClass) {
            $arr[] = app($providerClass);
        }

        return $arr;
    }

    /**
     * Register a mutator class, optionally providing an alias
     *
     * @param string $mutator
     * @param string|null $alias
     * @return self
     */
    public function registerMutator(string $mutator, string $alias = null): self
    {
        if (! class_exists($mutator) || ! in_array(MutatorContract::class, class_implements($mutator))) {
            throw PropertyException::invalidMutator($mutator);
        }

        $this->mutators[] = $mutator;

        if ($alias) {
            $this->mutatorAliases[$alias] = $mutator;
        }

        return $this;
    }

    /**
     * Resolve an alias, Object or class to a valid Mutator class. If the provided $mutator value
     * is not valid for any reason, null is returned.
     *
     * @param mixed $mutator
     * @return string|null
     */
    public function resolveMutatorClass($mutator):? string
    {
        $mutator = is_string($mutator)
            ? $mutator
            : (
                is_object($mutator)
                ? get_class($mutator)
                : null
            );

        if ($mutator && key_exists($mutator, $this->mutatorAliases)) {
            $mutator = $this->mutatorAliases[$mutator];
        }

        if (! $mutator || ! in_array($mutator, $this->mutators)) {
            return null;
        }

        return $mutator;
    }

    /**
     * Use the given contact model
     *
     * @param string $class
     * @return self
     */
    public function useContactModel(string $class): self
    {
        if (! $this->classExtendsParent($class, Model::class)) {
            throw InvalidModelException::fromInvalidClass($class);
        }

        $this->contactModel = $class;

        return $this;
    }

    /**
     * Use the given organization model
     *
     * @param string $class
     * @return self
     */
    public function useOrganizationModel(string $class): self
    {
        if (! $this->classExtendsParent($class, Model::class)) {
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
     * Process relay operations synchronously for the duration of $callback execution
     *
     * @param \Closure $callback
     * @return mixed The return result of $callback
     */
    public function sync(\Closure $callback)
    {
        return app(Dispatcher::class)->sync($callback);
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
