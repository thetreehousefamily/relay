<?php

namespace TheTreehouse\Relay;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use TheTreehouse\Relay\Exceptions\ProviderSupportException;
use TheTreehouse\Relay\Support\Contracts\RelayJobContract;

abstract class AbstractProvider
{
    /**
     * The display name for this provider
     * 
     * @var string|null $name
     */
    protected $name;
    
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
     * If the provider supports contacts, the class of the create contact job. If not
     * provided, one will be automatically generated based on the implementing class'
     * name.
     * 
     * @var string|null $createContactJob
     */
    protected $createContactJob;

    /**
     * If the provider supports organizations, the class of the create organization job.
     * If not provided, one will be automatically generated based on the implementing class'
     * name.
     * 
     * @var string|null $createOrganizationJob
     */
    protected $createOrganizationJob;

    /**
     * Get the name of this provider
     * 
     * @return string
     */
    public function name() : string
    {
        if (!$this->name) {
            $this->name = (string) Str::of(get_called_class())
                ->classBasename()
                ->replace('Relay', '')
                ->snake(' ')
                ->title();
        }

        return $this->name;
    }

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
     * @throws \TheTreehouse\Relay\Exceptions\ProviderSupportException Thrown if the provider does not support contacts
     */
    public function contactExists(Model $contact): bool
    {
        $this->guardContactSupport();

        return (bool) $contact->{$this->contactModelColumn()};
    }

    /**
     * Given an organization model instance, determine if it exists on the provider
     * 
     * @param \Illuminate\Database\Eloquent\Model $organization
     * @return bool
     * @throws \TheTreehouse\Relay\Exceptions\ProviderSupportException Thrown if the provider does not support organizations
     */
    public function organizationExists(Model $organization): bool
    {
        $this->guardOrganizationSupport();

        return (bool) $organization->{$this->organizationModelColumn()};
    }

    /**
     * Determine the database column name for storing the provider's id for the
     * contact record
     * 
     * @return string
     * @throws \TheTreehouse\Relay\Exceptions\ProviderSupportException Thrown if the provider does not support contacts
     */
    public function contactModelColumn(): string
    {
        $this->guardContactSupport();

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
     * @throws \TheTreehouse\Relay\Exceptions\ProviderSupportException Thrown if the provider does not support organizations
     */
    public function organizationModelColumn(): string
    {
        $this->guardOrganizationSupport();

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
        return (string) Str::of(get_called_class())
            ->classBasename()
            ->replace('Relay', '')
            ->snake()
            ->append('_id');
    }

    /**
     * Return a job instance that will create the provided contact on the
     * provider's service
     * 
     * @param \Illuminate\Database\Eloquent\Model $contact
     * @return \TheTreehouse\Relay\Support\Contracts\RelayJobContract
     * @throws \TheTreehouse\Relay\Exceptions\ProviderSupportException Thrown if the provider does not support contacts
     */
    public function createContactJob(Model $contact): RelayJobContract
    {
        $this->guardContactSupport();

        if (!$this->createContactJob) {
            $this->createContactJob = $this->guessNamespace()
                . '\\Jobs\\'
                . 'Create'
                . Str::of($this->name())->studly()
                . 'Contact';
        }

        return app($this->createContactJob, ['contact' => $contact]);
    }

    /**
     * Return a job instance that will create the provided organization on the
     * provider's service
     * 
     * @param \Illuminate\Database\Eloquent\Model $organization
     * @return \TheTreehouse\Relay\Support\Contracts\RelayJobContract
     * @throws \TheTreehouse\Relay\Exceptions\ProviderSupportException Thrown if the provider does not support organizations
     */
    public function createOrganizationJob(Model $organization): RelayJobContract
    {
        $this->guardOrganizationSupport();

        if (!$this->createOrganizationJob) {
            $this->createOrganizationJob = $this->guessNamespace()
                . '\\Jobs\\'
                . 'Create'
                . Str::of($this->name())->studly()
                . 'Organization';
        }

        return app($this->createOrganizationJob, ['organization' => $organization]);
    }

    /**
     * Throw a Provider Support exception if contacts are not supported by this
     * provider
     * 
     * @return self
     * @throws \TheTreehouse\Relay\Exceptions\ProviderSupportException
     */
    protected function guardContactSupport(): self
    {
        if (!$this->supportsContacts()) {
            throw ProviderSupportException::contactsNotSupported($this);
        }

        return $this;
    }

    /**
     * Throw a Provider Support exception if organizations are not supported by this
     * provider
     * 
     * @return self
     * @throws \TheTreehouse\Relay\Exceptions\ProviderSupportException
     */
    protected function guardOrganizationSupport(): self
    {
        if (!$this->supportsOrganizations()) {
            throw ProviderSupportException::organizationsNotSupported($this);
        }

        return $this;
    }

    /**
     * Guess the base namespace for the provider
     * 
     * @return string
     */
    private function guessNamespace() : string
    {
        $str = Str::of(get_called_class())
            ->explode('\\');

        $str->pop();

        return (string) $str->join('\\');
    }
}
