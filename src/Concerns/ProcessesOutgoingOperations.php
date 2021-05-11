<?php

namespace TheTreehouse\Relay\Concerns;

use Illuminate\Database\Eloquent\Model;

trait ProcessesOutgoingOperations
{
    /**
     * Relay a contact that was created within the application to the provider
     *
     * @param \Illuminate\Database\Eloquent\Model $contact
     * @param array $outboundProperties
     * @return void
     */
    public function contactCreated(Model $contact, array $outboundProperties)
    {
        return;
    }

    /**
     * Relay an organization that was created within the application to the provider
     *
     * @param \Illuminate\Database\Eloquent\Model $organization
     * @param array $outboundProperties
     * @return void
     */
    public function organizationCreated(Model $organization, array $outboundProperties)
    {
        return;
    }

    /**
     * Relay a contact that was updated within the application to the provider
     *
     * @param \Illuminate\Database\Eloquent\Model $contact
     * @param array $outboundProperties
     * @return void
     */
    public function contactUpdated(Model $contact, array $outboundProperties)
    {
        return;
    }

    /**
     * Relay an organization that was updated within the application to the provider
     *
     * @param \Illuminate\Database\Eloquent\Model $organization
     * @param array $outboundProperties
     * @return void
     */
    public function organizationUpdated(Model $organization, array $outboundProperties)
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
