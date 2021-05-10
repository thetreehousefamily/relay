<?php

namespace TheTreehouse\Relay\Concerns\Provider;

use Illuminate\Database\Eloquent\Model;

trait ProcessesOutgoingOperations
{
    /**
     * Relay a contact that was created within the application to the provider
     *
     * @param \Illuminate\Database\Eloquent\Model $contact
     * @return void
     */
    public function contactCreated(Model $contact)
    {
        return;
    }

    /**
     * Relay an organization that was created within the application to the provider
     *
     * @param \Illuminate\Database\Eloquent\Model $organization
     * @return void
     */
    public function organizationCreated(Model $organization)
    {
        return;
    }

    /**
     * Relay a contact that was updated within the application to the provider
     *
     * @param \Illuminate\Database\Eloquent\Model $contact
     * @return void
     */
    public function contactUpdated(Model $contact)
    {
        return;
    }

    /**
     * Relay an organization that was updated within the application to the provider
     *
     * @param \Illuminate\Database\Eloquent\Model $organization
     * @return void
     */
    public function organizationUpdated(Model $organization)
    {
        return;
    }

    /**
     * Relay a contact that was deleted within the application to the provider
     *
     * @param \Illuminate\Database\Eloquent\Model $contact
     * @return void
     */
    public function contactDeleted(Model $contact)
    {
        return;
    }

    /**
     * Relay an organization that was deleted within the application to the provider
     *
     * @param \Illuminate\Database\Eloquent\Model $organization
     * @return void
     */
    public function organizationDeleted(Model $organization)
    {
        return;
    }
}
