<?php

namespace TheTreehouse\Relay;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractProvider
{
    /**
     * Determines whether this provider supports the contact entity
     * 
     * @var bool
     */
    protected $supportsContacts = true;

    /**
     * Determines whether this provider supports the organizations entity
     * 
     * @var bool
     */
    protected $supportsOrganizations = true;

    /**
     * Determine if this provider supports the contact entity
     * 
     * @return bool
     */
    public function supportsContacts(): bool
    {
        return $this->supportsContacts;
    }

    /**
     * Determine if this provider supports the organizations entity
     * 
     * @return bool
     */
    public function supportsOrganizations(): bool
    {
        return $this->supportsOrganizations;
    }

    /**
     * Return a job instance that will create the provided contact on the
     * provider's service
     * 
     * @param \Illuminate\Database\Eloquent\Model $contact
     */
    public function createContactJob(Model $contact)
    {
        return 'FIXME: This should return a job... Is this method abstract?';
    }

    /**
     * Return a job instance that will create the provided organization on the
     * provider's service
     * 
     * @param \Illuminate\Database\Eloquent\Model $organization
     */
    public function createOrganizationJob(Model $organization)
    {
        return 'FIXME: This should return a job... Is this method abstract?';
    }
}
