<?php

namespace TheTreehouse\Relay\Tests\Fixtures\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Assert as PHPUnit;
use TheTreehouse\Relay\AbstractProvider;

class FakeProvider extends AbstractProvider
{
    /**
     * The contact records that would have been created
     * 
     * @var \Illuminate\Database\Eloquent\Model[]
     */
    protected array $createdContacts = [];

    /**
     * The organization records that would have been created
     * 
     * @var \Illuminate\Database\Eloquent\Model[]
     */
    protected array $createdOrganizations = [];

    /**
     * Manually override the contacts support
     * 
     * @param bool $supportsContacts
     */
    public function setSupportsContacts(bool $supportsContacts): self
    {
        $this->supportsContacts = $supportsContacts;

        return $this;
    }

    /**
     * Manually override the organizations support
     * 
     * @param bool $supportsOrganizations
     */
    public function setSupportsOrganizations(bool $supportsOrganizations): self
    {
        $this->supportsOrganizations = $supportsOrganizations;

        return $this;
    }

    /**
     * Return a stub job that would create a contact on this fictitious service
     * 
     * @param \Illuminate\Database\Eloquent\Model $contact
     */
    public function createContactJob(Model $contact)
    {
        $this->createdContacts[] = $contact;

        return '.... some job stub';
    }

    /**
     * Return a stub job that would create an organization on this fictitious service
     * 
     * @param \Illuminate\Database\Eloquent\Model $organization
     */
    public function createOrganizationJob(Model $organization)
    {
        $this->createdOrganizations[] = $organization;

        return '.... some job stub';
    }

    /**
     * Assert that the provider was not asked to create any contacts
     * 
     * @return static
     */
    public function assertNoContactsCreated(): self
    {
        PHPUnit::assertEmpty($this->createdContacts, 'Expected to create 0 contact records, actually created ' . count($this->createdContacts));

        return $this;
    }

    /**
     * Assert that the provider was not asked to create any organizations
     * 
     * @return static
     */
    public function assertNoOrganizationsCreated(): self
    {
        PHPUnit::assertEmpty($this->createdOrganizations, 'Expected to create 0 organization records, actually created ' . count($this->createdOrganizations));

        return $this;
    }
}
