<?php

namespace TheTreehouse\Relay;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
     * If the provider supports contacts, this property contains the column name on
     * the contact model which will hold the external identifier for this service. If
     * not provided, one will be automatically generated based on the implementing
     * class' name.
     * 
     * @var string|null $contactModelColumn
     */
    protected $contactModelColumn;

    /**
     * If the provider supports organizations, this property contains the column name on
     * the organization model which will hold the external identifier for this service. If
     * not provided, one will be automatically generated based on the implementing
     * class' name.
     * 
     * @var string|null $organizationModelColumn
     */
    protected $organizationModelColumn;

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
     * Given a contact model instance, determine if it exists on the provider
     * 
     * @param \Illuminate\Database\Eloquent\Model $contact
     * @return bool
     */
    public function contactExists(Model $contact): bool
    {
        return (bool) $contact->{$this->contactModelColumn()};
    }

    /**
     * Given an organization model instance, determine if it exists on the provider
     * 
     * @param \Illuminate\Database\Eloquent\Model $organization
     * @return bool
     */
    public function organizationExists(Model $organization): bool
    {
        return (bool) $organization->{$this->organizationModelColumn()};
    }

    /**
     * Determine the database column name for storing the provider's id for the
     * contact record
     * 
     * @return string
     */
    public function contactModelColumn(): string
    {
        if (!$this->contactModelColumn) {
            $this->contactModelColumn = $this->generateDefaultModelColumn();
        }

        return $this->contactModelColumn;
    }

    /**
     * Determine the database column name for storing the provider's id for the
     * organization record
     * 
     * @return string
     */
    public function organizationModelColumn(): string
    {
        if (!$this->organizationModelColumn) {
            $this->organizationModelColumn = $this->generateDefaultModelColumn();
        }

        return $this->organizationModelColumn;
    }

    /**
     * Generate the default model column string for the provider
     * 
     * @return string
     */
    private function generateDefaultModelColumn(): string
    {
        return (string) Str::substr(
            Str::of(get_called_class())
                ->replace('Relay', '')
                ->append('_id')
                ->snake()
                ->lower()
                ->explode('\\')
                ->last(),
            1
        );
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
