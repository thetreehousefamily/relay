<?php

namespace TheTreehouse\Relay\Concerns\Provider;

use Illuminate\Database\Eloquent\Model;

trait ProcessesOutgoingOperations
{
    /**
     * Relay a contact that was created within the application to the provider
     *
     * @param \Illuminate\Database\Eloquent\Model $contact
     * @return self
     */
    public function contactCreated(Model $contact)
    {
        return $this;
    }

    /**
     * Relay an organization that was created within the application to the provider
     *
     * @param \Illuminate\Database\Eloquent\Model $organization
     * @return self
     */
    public function organizationCreated(Model $organization)
    {
        return $this;
    }
}
