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
     * Return the registered providers
     *
     * @return string[]
     */
    public function getRegisteredProviders(): array;

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