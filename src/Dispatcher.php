<?php

namespace TheTreehouse\Relay;

use Closure;
use Illuminate\Database\Eloquent\Model;
use TheTreehouse\Relay\Exceptions\InvalidProviderException;
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
     * Flag to determine whether processing execution should happen synchronously
     *
     * @var bool
     */
    protected $isSync = false;

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
     * Provide a callback to execute, and execute that callback processing any operations
     * synchronously for the duration of its execution.
     *
     * @param \Closure $callback
     * @return mixed The return result of $callback
     */
    public function sync(Closure $callback)
    {
        $this->isSync = true;

        $result = $callback();

        $this->isSync = false;

        return $result;
    }

    /**
     * Process a manual upsert request from an entity
     *
     * @param \Illuminate\Database\Eloquent\Model $entity
     * @param \TheTreehouse\Relay\AbstractProvider|string|null $provider
     * @return static
     */
    public function processManualRelay(Model $entity, $provider = null): Dispatcher
    {
        $class = get_class($entity);

        $type =
            $class === config('relay.contact')
            ? 'Contact'
            : (
                $class === config('relay.organization')
                ? 'Organization'
                : null
            );

        if (! $type) {
            return $this;
        }

        return $this->{"relayUpdated{$type}"}($entity, $provider);
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
     * @param \TheTreehouse\Relay\AbstractProvider|string|null $provider
     * @return static
     */
    public function relayUpdatedContact(Model $contact, $provider = null): Dispatcher
    {
        return $this->processUpdated($contact, RelayEntityAction::ENTITY_CONTACT, $provider);
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
     * @param \TheTreehouse\Relay\AbstractProvider|string|null $provider
     * @return static
     */
    public function relayUpdatedOrganization(Model $organization, $provider = null): Dispatcher
    {
        return $this->processUpdated($organization, RelayEntityAction::ENTITY_ORGANIZATION, $provider);
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

            if (! config("{$provider->configKey()}.auto", true)) {
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
     * @param \TheTreehouse\Relay\AbstractProvider|string|null $provider
     * @return self
     */
    protected function processUpdated(Model $entity, string $entityType, $provider = null): Dispatcher
    {
        if (! $this->relay->{"supports".ucfirst($entityType)."s"}()) {
            return $this;
        }

        $providers = $provider
            ? [$this->resolveProvider($provider)]
            : $this->relay->getProviders();

        foreach ($providers as $provider) {
            if (! $provider->{"supports".ucfirst($entityType)."s"}()) {
                continue;
            }

            if (! config("{$provider->configKey()}.auto", true)) {
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

            if (! config("{$provider->configKey()}.auto", true)) {
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
        $method = $this->isSync
            ? 'dispatchSync'
            : 'dispatch';

        RelayEntityAction::{$method}(
            $entity,
            $entityType,
            $action,
            $provider
        );

        return $this;
    }

    /**
     * Resolve a provider instance or string, ensuring it is a valid, registered
     * Relay Provider
     *
     * @param \TheTreehouse\Relay\AbstractProvider|string $provider
     * @return \TheTreehouse\Relay\AbstractProvider
     */
    protected function resolveProvider($provider): AbstractProvider
    {
        $providerClass = is_string($provider)
            ? $provider
            : get_class($provider);

        foreach ($this->relay->getProviders() as $registeredProvider) {
            if (get_class($registeredProvider) === $providerClass) {
                return $registeredProvider;
            }
        }

        throw InvalidProviderException::midApplication($providerClass);
    }
}
