<?php

namespace TheTreehouse\Relay;

use Illuminate\Database\Eloquent\Model;
use TheTreehouse\Relay\Jobs\RelayEntityAction;
use TheTreehouse\Relay\Support\Contracts\RelayContract;

class Dispatcher
{
    /**
     * The Relay singleton instance
     *
     * @var \TheTreehouse\Relay\Relay
     */
    protected RelayContract $relay;

    /**
     * Instantiate the Dispatcher singleton
     *
     * @param \TheTreehouse\Relay\Support\Contracts\RelayContract $relay
     * @return void
     */
    public function __construct(RelayContract $relay)
    {
        $this->relay = $relay;
    }

    /**
     * Process the relay for a created contact
     *
     * @param \Illuminate\Database\Eloquent\Model $contact
     * @return static
     */
    public function relayCreatedContact(Model $contact): Dispatcher
    {
        return $this->processCreated($contact, RelayEntityAction::ENTITY_CONTACT);
    }

    /**
     * Process the relay for an updated contact
     *
     * @param \Illuminate\Database\Eloquent\Model $contact
     * @return static
     */
    public function relayUpdatedContact(Model $contact): Dispatcher
    {
        return $this->processUpdated($contact, RelayEntityAction::ENTITY_CONTACT);
    }

    /**
     * Process the relay for a deleted contact
     *
     * @param \Illuminate\Database\Eloquent\Model $contact
     * @return static
     */
    public function relayDeletedContact(Model $contact): Dispatcher
    {
        return $this->processDeleted($contact, RelayEntityAction::ENTITY_CONTACT);
    }

    /**
     * Process the relay for a created organization
     *
     * @param \Illuminate\Database\Eloquent\Model $organization
     * @return static
     */
    public function relayCreatedOrganization(Model $organization): Dispatcher
    {
        return $this->processCreated($organization, RelayEntityAction::ENTITY_ORGANIZATION);
    }

    /**
     * Process the relay for an updated organization
     *
     * @param \Illuminate\Database\Eloquent\Model $organization
     * @return static
     */
    public function relayUpdatedOrganization(Model $organization): Dispatcher
    {
        return $this->processUpdated($organization, RelayEntityAction::ENTITY_ORGANIZATION);
    }

    /**
     * Process the relay for a deleted organization
     *
     * @param \Illuminate\Database\Eloquent\Model $organization
     * @return static
     */
    public function relayDeletedOrganization(Model $organization): Dispatcher
    {
        return $this->processDeleted($organization, RelayEntityAction::ENTITY_ORGANIZATION);
    }

    /**
     * Process a created entity
     * 
     * @param \Illuminate\Database\Eloquent\Model $entity
     * @param string $entityType
     * @return self
     */
    protected function processCreated(Model $entity, string $entityType): Dispatcher
    {
        if (! $this->relay->{"supports".ucfirst($entityType)."s"}()) {
            return $this;
        }

        foreach ($this->relay->getProviders() as $provider) {
            if (! $provider->{"supports".ucfirst($entityType)."s"}() || $provider->{"{$entityType}Exists"}($entity)) {
                continue;
            }

            $this->dispatch(
                $entity,
                $entityType,
                RelayEntityAction::ACTION_CREATE,
                $provider
            );
        }

        return $this;
    }

    /**
     * Process an updated entity
     * 
     * @param \Illuminate\Database\Eloquent\Model $entity
     * @param string $entityType
     * @return self
     */
    protected function processUpdated(Model $entity, string $entityType): Dispatcher
    {
        if (! $this->relay->{"supports".ucfirst($entityType)."s"}()) {
            return $this;
        }

        foreach ($this->relay->getProviders() as $provider) {
            if (! $provider->{"supports".ucfirst($entityType)."s"}()) {
                continue;
            }

            $this->dispatch(
                $entity,
                $entityType,
                (
                    $provider->{"{$entityType}Exists"}($entity)
                    ? RelayEntityAction::ACTION_UPDATE
                    : RelayEntityAction::ACTION_CREATE
                ),
                $provider
            );
        }

        return $this;
    }

    /**
     * Process a deleted entity
     * 
     * @param \Illuminate\Database\Eloquent\Model $entity
     * @param string $entityType
     * @return self
     */
    protected function processDeleted(Model $entity, string $entityType): Dispatcher
    {
        if (! $this->relay->{"supports".ucfirst($entityType)."s"}()) {
            return $this;
        }

        foreach ($this->relay->getProviders() as $provider) {
            if (
                ! $provider->{"supports".ucfirst($entityType)."s"}()
                || ! $provider->{"{$entityType}Exists"}($entity)
            ) {
                continue;
            }

            $this->dispatch(
                $entity,
                $entityType,
                RelayEntityAction::ACTION_DELETE,
                $provider
            );
        }

        return $this;
    }

    /**
     * Configure and dispatch a relay job
     *
     * @param \Illuminate\Database\Eloquent\Model $entity
     * @param string $entityType
     * @param string $action
     * @param \TheTreehouse\Relay\AbstractProvider $provider
     * @return self
     */
    protected function dispatch(Model $entity, string $entityType, string $action, AbstractProvider $provider): self
    {
        RelayEntityAction::dispatch(
            $entity,
            $entityType,
            $action,
            $provider

        );

        return $this;
    }
}
