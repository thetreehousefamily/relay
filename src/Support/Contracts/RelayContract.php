<?php

namespace TheTreehouse\Relay\Support\Contracts;

interface RelayContract
{
    /**
     * Register a provider by its class name
     *
     * @param string $class
     * @return self
     */
    public function registerProvider(string $class): self;

    /**
     * Return the registered provider classes
     *
     * @return string[]
     */
    public function getRegisteredProviders(): array;

    /**
     * Shortcut for mapping the registered provider classes to resolved instances
     *
     * @return \TheTreehouse\Relay\AbstractProvider[]
     */
    public function getProviders(): array;

    /**
     * Register a mutator class, optionally providing an alias
     *
     * @param string $mutator
     * @param string|null $alias
     * @return self
     */
    public function registerMutator(string $mutator, string $alias = null): self;

    /**
     * Get a registered mutator instance by its class name, or by passing its instance. If the
     * mutator is not registered, null will be returned. If $mutator is a valid instance, the
     * original instance will be returned.
     *
     * @param mixed $mutator
     * @return \TheTreehouse\Relay\Support\Contracts\MutatorContract|null
     */
    public function getMutator($mutator):? MutatorContract;

    /**
     * Use the given contact model
     *
     * @param string $class
     * @return self
     */
    public function useContactModel(string $class): self;

    /**
     * Use the given organization model
     *
     * @param string $class
     * @return self
     */
    public function useOrganizationModel(string $class): self;

    /**
     * Return the configured contact model class name, or null if it does not exist or
     * is not supported by the application
     *
     * @return string|null
     */
    public function contactModel(): ? string;

    /**
     * Return the configured organization model class name, or null if it does not exist
     * or is not supported by the application
     *
     * @return string|null
     */
    public function organizationModel(): ? string;

    /**
     * Return a boolean value, indicating whether the application supports the contacts
     * concept, depending on its configuration.
     *
     * @return bool
     */
    public function supportsContacts(): bool;

    /**
     * Return a boolean value, indicating whether the application supports the organizations
     * concept, depending on its configuration.
     *
     * @return bool
     */
    public function supportsOrganizations(): bool;
}
