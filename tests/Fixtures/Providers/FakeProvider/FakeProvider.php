<?php

namespace TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Assert as PHPUnit;
use TheTreehouse\Relay\AbstractProvider;
use TheTreehouse\Relay\Support\Contracts\RelayJobContract;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs\CreateFakeContact;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs\CreateFakeOrganization;

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

    /** @inheritdoc */
    protected $createContactJob = CreateFakeContact::class;

    /** @inheritdoc */
    protected $createOrganizationJob = CreateFakeOrganization::class;

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
     * @return \TheTreehouse\Relay\Support\Contracts\RelayJobContract
     */
    public function createContactJob(Model $contact): RelayJobContract
    {
        $this->createdContacts[] = $contact;

        return parent::createContactJob($contact);
    }

    /**
     * Return a stub job that would create an organization on this fictitious service
     * 
     * @param \Illuminate\Database\Eloquent\Model $organization
     * @return \TheTreehouse\Relay\Support\Contracts\RelayJobContract
     */
    public function createOrganizationJob(Model $organization): RelayJobContract
    {
        $this->createdOrganizations[] = $organization;

        return parent::createOrganizationJob($organization);
    }

    /**
     * Assert that a contact was created. Optionally, assert that the provided contact was
     * specifically created
     * 
     * @param \Illuminate\Database\Eloquent\Model|null $contact
     * @return static
     */
    public function assertContactCreated($contact = null): self
    {
        PHPUnit::assertNotEmpty($this->createdContacts, 'Expected to create at least 1 contact record, actually created none');

        if ($contact) {
            PHPUnit::assertContains($contact, $this->createdContacts, 'The expected contact was not created');
        }

        return $this;
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
     * Assert that an organization was created. Optionally, assert that the provided organization
     * was specifically created
     * 
     * @param \Illuminate\Database\Eloquent\Model|null $organization
     * @return static
     */
    public function assertOrganizationCreated($organization = null): self
    {
        PHPUnit::assertNotEmpty($this->createdOrganizations, 'Expected to create at least 1 organization record, actually created none');

        if ($organization) {
            PHPUnit::assertContains($organization, $this->createdOrganizations, 'The expected organization was not created');
        }
        
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
