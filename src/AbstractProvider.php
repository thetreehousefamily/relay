<?php

namespace TheTreehouse\Relay;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use TheTreehouse\Relay\Concerns\Provider\ProcessesIncomingOperations;
use TheTreehouse\Relay\Concerns\Provider\ProcessesOutgoingOperations;
use TheTreehouse\Relay\Exceptions\ProviderSupportException;

abstract class AbstractProvider
{
    use ProcessesIncomingOperations;
    use ProcessesOutgoingOperations;

    /**
     * The display name for this provider
     *
     * @var string|null
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
     * @var string|null
     */
    protected $contactModelColumn;

    /**
     * If the provider supports organizations, this property contains the column name on
     * the organization model which will hold the external identifier for this service. If
     * not provided, one will be automatically generated based on the implementing
     * class' name.
     *
     * @var string|null
     */
    protected $organizationModelColumn;

    /**
     * Get the name of this provider
     *
     * @return string
     */
    public function name() : string
    {
        if (! $this->name) {
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

        if (! $this->contactModelColumn) {
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

        if (! $this->organizationModelColumn) {
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
     * Throw a Provider Support exception if contacts are not supported by this
     * provider
     *
     * @return self
     * @throws \TheTreehouse\Relay\Exceptions\ProviderSupportException
     */
    protected function guardContactSupport(): self
    {
        if (! $this->supportsContacts()) {
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
        if (! $this->supportsOrganizations()) {
            throw ProviderSupportException::organizationsNotSupported($this);
        }

        return $this;
    }
}
