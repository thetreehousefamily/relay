<?php

namespace TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Assert as PHPUnit;
use TheTreehouse\Relay\AbstractProvider;
use TheTreehouse\Relay\Support\Contracts\RelayJobContract;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs\CreateFakeContact;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs\CreateFakeOrganization;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs\DeleteFakeContact;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs\DeleteFakeOrganization;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs\UpdateFakeContact;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs\UpdateFakeOrganization;

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
     * The contact records that would have been updated
     * 
     * @var \Illuminate\Database\Eloquent\Model[]
     */
    protected array $updatedContacts = [];

    /**
     * The organization records that would have been updated
     * 
     * @var \Illuminate\Database\Eloquent\Model[]
     */
    protected array $updatedOrganizations = [];

    /**
     * The contact records that would have been deleted
     * 
     * @var \Illuminate\Database\Eloquent\Model[]
     */
    protected array $deletedContacts = [];

    /**
     * The organization records that would have been deleted
     * 
     * @var \Illuminate\Database\Eloquent\Model[]
     */
    protected array $deletedOrganizations = [];

    /** @inheritdoc */
    protected $createContactJob = CreateFakeContact::class;

    /** @inheritdoc */
    protected $createOrganizationJob = CreateFakeOrganization::class;

    /** @inheritdoc */
    protected $updateContactJob = UpdateFakeContact::class;

    /** @inheritdoc */
    protected $updateOrganizationJob = UpdateFakeOrganization::class;

    /** @inheritdoc */
    protected $deleteContactJob = DeleteFakeContact::class;

    /** @inheritdoc */
    protected $deleteOrganizationJob = DeleteFakeOrganization::class;

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
     * Return a stub job that would update a contact on this fictitious service
     * 
     * @param \Illuminate\Database\Eloquent\Model $contact
     * @return \TheTreehouse\Relay\Support\Contracts\RelayJobContract
     */
    public function updateContactJob(Model $contact): RelayJobContract
    {
        $this->updatedContacts[] = $contact;

        return parent::updateContactJob($contact);
    }

    /**
     * Return a stub job that would update an organization on this fictitious service
     * 
     * @param \Illuminate\Database\Eloquent\Model $organization
     * @return \TheTreehouse\Relay\Support\Contracts\RelayJobContract
     */
    public function updateOrganizationJob(Model $organization): RelayJobContract
    {
        $this->updatedOrganizations[] = $organization;

        return parent::updateOrganizationJob($organization);
    }

    /**
     * Return a stub job that would delete a contact on this fictitious service
     * 
     * @param \Illuminate\Database\Eloquent\Model $contact
     * @return \TheTreehouse\Relay\Support\Contracts\RelayJobContract
     */
    public function deleteContactJob(Model $contact): RelayJobContract
    {
        $this->deletedContacts[] = $contact;

        return parent::deleteContactJob($contact);
    }

    /**
     * Return a stub job that would delete an organization on this fictitious service
     * 
     * @param \Illuminate\Database\Eloquent\Model $organization
     * @return \TheTreehouse\Relay\Support\Contracts\RelayJobContract
     */
    public function deleteOrganizationJob(Model $organization): RelayJobContract
    {
        $this->deletedOrganizations[] = $organization;

        return parent::deleteOrganizationJob($organization);
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

    /**
     * Assert that a contact was updated. Optionally, assert that the provided contact was
     * specifically updated
     * 
     * @param \Illuminate\Database\Eloquent\Model|null $contact
     * @return static
     */
    public function assertContactUpdated($contact = null): self
    {
        PHPUnit::assertNotEmpty($this->updatedContacts, 'Expected to update at least 1 contact record, actually updated none');

        if ($contact) {
            PHPUnit::assertContains($contact, $this->updatedContacts, 'The expected contact was not updated');
        }

        return $this;
    }

    /**
     * Assert that the provider was not asked to update any contacts
     * 
     * @return static
     */
    public function assertNoContactsUpdated(): self
    {
        PHPUnit::assertEmpty($this->updatedContacts, 'Expected to update 0 contact records, actually updated ' . count($this->updatedContacts));

        return $this;
    }

    /**
     * Assert that an organization was updated. Optionally, assert that the provided organization
     * was specifically updated
     * 
     * @param \Illuminate\Database\Eloquent\Model|null $organization
     * @return static
     */
    public function assertOrganizationUpdated($organization = null): self
    {
        PHPUnit::assertNotEmpty($this->updatedOrganizations, 'Expected to update at least 1 organization record, actually updated none');

        if ($organization) {
            PHPUnit::assertContains($organization, $this->updatedOrganizations, 'The expected organization was not updated');
        }
        
        return $this;
    }

    /**
     * Assert that the provider was not asked to update any organizations
     * 
     * @return static
     */
    public function assertNoOrganizationsUpdated(): self
    {
        PHPUnit::assertEmpty($this->updatedOrganizations, 'Expected to update 0 organization records, actually updated ' . count($this->updatedOrganizations));

        return $this;
    }

    /**
     * Assert that a contact was deleted. Optionally, assert that the provided contact was
     * specifically deleted
     * 
     * @param \Illuminate\Database\Eloquent\Model|null $contact
     * @return static
     */
    public function assertContactDeleted($contact = null): self
    {
        PHPUnit::assertNotEmpty($this->deletedContacts, 'Expected to delete at least 1 contact record, actually deleted none');

        if ($contact) {
            PHPUnit::assertContains($contact, $this->deletedContacts, 'The expected contact was not deleted');
        }

        return $this;
    }

    /**
     * Assert that the provider was not asked to delete any contacts
     * 
     * @return static
     */
    public function assertNoContactsDeleted(): self
    {
        PHPUnit::assertEmpty($this->deletedContacts, 'Expected to delete 0 contact records, actually deleted ' . count($this->deletedContacts));

        return $this;
    }

    /**
     * Assert that an organization was deleted. Optionally, assert that the provided organization
     * was specifically deleted
     * 
     * @param \Illuminate\Database\Eloquent\Model|null $organization
     * @return static
     */
    public function assertOrganizationDeleted($organization = null): self
    {
        PHPUnit::assertNotEmpty($this->deletedOrganizations, 'Expected to delete at least 1 organization record, actually deleted none');

        if ($organization) {
            PHPUnit::assertContains($organization, $this->deletedOrganizations, 'The expected organization was not updated');
        }
        
        return $this;
    }

    /**
     * Assert that the provider was not asked to delete any organizations
     * 
     * @return static
     */
    public function assertNoOrganizationsDeleted(): self
    {
        PHPUnit::assertEmpty($this->deletedOrganizations, 'Expected to delete 0 organization records, actually updated ' . count($this->deletedOrganizations));

        return $this;
    }
}
