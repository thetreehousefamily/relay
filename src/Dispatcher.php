<?php

namespace TheTreehouse\Relay;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\PendingDispatch;
use TheTreehouse\Relay\Support\Contracts\RelayContract;
use TheTreehouse\Relay\Support\Contracts\RelayJobContract;

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
        if (!$this->relay->supportsContacts()) {
            return $this;
        }

        foreach($this->relay->getProviders() as $provider) {
            if (!$provider->supportsContacts() || $provider->contactExists($contact)) {
                continue;
            }

            $job = $provider->createContactJob($contact);

            $this->dispatch($job);
        }

        return $this;
    }

    /**
     * Process the relay for an updated contact
     * 
     * @param \Illuminate\Database\Eloquent\Model $contact
     * @return static
     */
    public function relayUpdatedContact(Model $contact): Dispatcher
    {
        if (!$this->relay->supportsContacts()) {
            return $this;
        }

        foreach($this->relay->getProviders() as $provider) {
            if (!$provider->supportsContacts()) {
                return $this;
            }

            $job = $provider->contactExists($contact)
                ? $provider->updateContactJob($contact)
                : $provider->createContactJob($contact);

            $this->dispatch($job);
        }

        return $this;
    }

    /**
     * Process the relay for a deleted contact
     * 
     * @param \Illuminate\Database\Eloquent\Model $contact
     * @return static
     */
    public function relayDeletedContact(Model $contact): Dispatcher
    {
        if (!$this->relay->supportsContacts()) {
            return $this;
        }

        foreach($this->relay->getProviders() as $provider) {
            if (!$provider->supportsContacts()) {
                return $this;
            }

            if ($provider->contactExists($contact)) {
                $this->dispatch($provider->deleteContactJob($contact));
            }
        }

        return $this;
    }

    /**
     * Process the relay for a created organization
     * 
     * @param \Illuminate\Database\Eloquent\Model $organization
     * @return static
     */
    public function relayCreatedOrganization(Model $organization): Dispatcher
    {
        if (!$this->relay->supportsOrganizations()) {
            return $this;
        }

        foreach($this->relay->getProviders() as $provider) {
            if (!$provider->supportsOrganizations() || $provider->organizationExists($organization)) {
                continue;
            }

            $job = $provider->createOrganizationJob($organization);
            
            $this->dispatch($job);
        }
        return $this;
    }

    /**
     * Process the relay for an updated organization
     * 
     * @param \Illuminate\Database\Eloquent\Model $organization
     * @return static
     */
    public function relayUpdatedOrganization(Model $organization): Dispatcher
    {
        if (!$this->relay->supportsOrganizations()) {
            return $this;
        }

        foreach($this->relay->getProviders() as $provider) {
            if (!$provider->supportsOrganizations()) {
                return $this;
            }

            $job = $provider->organizationExists($organization)
                ? $provider->updateOrganizationJob($organization)
                : $provider->createOrganizationJob($organization);

            $this->dispatch($job);
        }

        return $this;
    }

    /**
     * Process the relay for a deleted organization
     * 
     * @param \Illuminate\Database\Eloquent\Model $organization
     * @return static
     */
    public function relayDeletedOrganization(Model $organization): Dispatcher
    {
        if (!$this->relay->supportsOrganizations()) {
            return $this;
        }

        foreach($this->relay->getProviders() as $provider) {
            if (!$provider->supportsOrganizations()) {
                return $this;
            }

            if ($provider->organizationExists($organization)) {
                $this->dispatch($provider->deleteOrganizationJob($organization));
            }
        }

        return $this;
    }

    /**
     * Dispatch the provided job instance
     * 
     * @param \TheTreehouse\Relay\Support\Contracts\RelayJobContract $job
     * @return \Illuminate\Foundation\Bus\PendingDispatch
     */
    protected function dispatch(RelayJobContract $job): PendingDispatch
    {
        return new PendingDispatch($job);
    }
}