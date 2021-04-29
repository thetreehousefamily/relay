<?php

namespace TheTreehouse\Relay;

use Illuminate\Database\Eloquent\Model;
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
        return $this;
    }

    /**
     * Process the relay for a created organization
     * 
     * @param \Illuminate\Database\Eloquent\Model $organization
     * @return static
     */
    public function relayCreatedOrganization(Model $contact): Dispatcher
    {
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
        return $this;
    }
}