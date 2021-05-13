<?php

namespace TheTreehouse\Relay\Support;

use TheTreehouse\Relay\Relay;
use TheTreehouse\Relay\Support\Contracts\MutatorContract;
use TheTreehouse\Relay\Support\Contracts\RelayContract;

class FakeRelay implements RelayContract
{
    /**
     * The original Relay singleton instance
     *
     * @var \TheTreehouse\Relay\Relay
     */
    protected $relay;

    /**
     * Override fake value for supports contacts
     *
     * @var bool|null
     */
    protected ?bool $supportsContacts = null;

    /**
     * Override fake value for supports organizations
     *
     * @var bool|null
     */
    protected ?bool $supportsOrganizations = null;

    /**
     * Instantiate the Fake Relay with the original instance, so that calls can be
     * proxied if not faked
     *
     * @param \TheTreehouse\Relay\Relay $relay
     * @return void
     */
    public function __construct(Relay $relay)
    {
        $this->relay = $relay;
    }

    /** @inheritdoc */
    public function registerProvider(string $class): RelayContract
    {
        return $this->relay->registerProvider($class);
    }
    
    /** @inheritdoc */
    public function getRegisteredProviders(): array
    {
        return $this->relay->getRegisteredProviders();
    }

    /** @inheritdoc */
    public function getProviders(): array
    {
        return $this->relay->getProviders();
    }

    /** @inheritdoc */
    public function registerMutator(string $mutator, ?string $alias = null): RelayContract
    {
        return $this->relay->registerMutator($mutator, $alias);
    }

    /** @inheritdoc */
    public function getMutator($mutator): ?MutatorContract
    {
        return $this->relay->getMutator($mutator);
    }

    /** @inheritdoc */
    public function useContactModel(string $class): RelayContract
    {
        return $this->relay->useContactModel($class);
    }

    /** @inheritdoc */
    public function useOrganizationModel(string $class): RelayContract
    {
        return $this->relay->useOrganizationModel($class);
    }

    /** @inheritdoc */
    public function contactModel(): ?string
    {
        return $this->relay->contactModel();
    }
    
    /** @inheritdoc */
    public function organizationModel(): ?string
    {
        return $this->relay->organizationModel();
    }

    /**
     * Manually set the supports contacts value
     *
     * @param bool $supportsContacts
     * @return static
     */
    public function setSupportsContacts(bool $supportsContacts): self
    {
        $this->supportsContacts = $supportsContacts;

        return $this;
    }

    /** @inheritdoc */
    public function supportsContacts(): bool
    {
        if ($this->supportsContacts === null) {
            return $this->relay->supportsContacts();
        }

        return $this->supportsContacts;
    }

    /**
     * Manually set the supports organizations value
     *
     * @param bool $supportsOrganizations
     * @return static
     */
    public function setSupportsOrganizations(bool $supportsOrganizations): self
    {
        $this->supportsOrganizations = $supportsOrganizations;

        return $this;
    }

    /** @inheritdoc */
    public function supportsOrganizations(): bool
    {
        if ($this->supportsOrganizations === null) {
            return $this->relay->supportsOrganizations();
        }

        return $this->supportsOrganizations;
    }
}
