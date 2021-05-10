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
     * @inheritdoc
     */
    public function contactCreated(Model $contact)
    {
        $this->createdContacts[] = $contact;
    }

    /**
     * @inheritdoc
     */
    public function organizationCreated(Model $organization)
    {
        $this->createdOrganizations[] = $organization;
    }

    /**
     * @inheritdoc
     */
    public function contactUpdated(Model $contact)
    {
        $this->updatedContacts[] = $contact;
    }

    /**
     * @inheritdoc
     */
    public function organizationUpdated(Model $organization)
    {
        $this->updatedOrganizations[] = $organization;
    }

    /**
     * @inheritdoc
     */
    public function contactDeleted(Model $contact)
    {
        $this->deletedContacts[] = $contact;
    }

    /**
     * @inheritdoc
     */
    public function organizationDeleted(Model $organization)
    {
        $this->deletedOrganizations[] = $organization;
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
        return $this->assertEntityActioned('create', 'contact', $contact);
    }

    /**
     * Assert that the provider was not asked to create any contacts
     *
     * @return static
     */
    public function assertNoContactsCreated(): self
    {
        return $this->assertNoEntitiesActioned('create', 'contact');
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
        return $this->assertEntityActioned('create', 'organization', $organization);
    }

    /**
     * Assert that the provider was not asked to create any organizations
     *
     * @return static
     */
    public function assertNoOrganizationsCreated(): self
    {
        return $this->assertNoEntitiesActioned('create', 'organization');
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
        return $this->assertEntityActioned('update', 'contact', $contact);
    }

    /**
     * Assert that the provider was not asked to update any contacts
     *
     * @return static
     */
    public function assertNoContactsUpdated(): self
    {
        return $this->assertNoEntitiesActioned('update', 'contact');
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
        return $this->assertEntityActioned('update', 'organization', $organization);
    }

    /**
     * Assert that the provider was not asked to update any organizations
     *
     * @return static
     */
    public function assertNoOrganizationsUpdated(): self
    {
        return $this->assertNoEntitiesActioned('update', 'organization');
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
        return $this->assertEntityActioned('delete', 'contact', $contact);
    }

    /**
     * Assert that the provider was not asked to delete any contacts
     *
     * @return static
     */
    public function assertNoContactsDeleted(): self
    {
        return $this->assertNoEntitiesActioned('delete', 'contact');
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
        return $this->assertEntityActioned('delete', 'organization', $organization);
    }

    /**
     * Assert that the provider was not asked to delete any organizations
     *
     * @return static
     */
    public function assertNoOrganizationsDeleted(): self
    {
        return $this->assertNoEntitiesActioned('delete', 'organization');
    }

    private function assertEntityActioned($action, $entity, $model = null): self
    {
        $actionPast = $this->pastTense($action);

        /** @var array $bucket */
        $bucket = $this->{$actionPast.ucfirst($entity)."s"};

        PHPUnit::assertNotEmpty(
            $bucket,
            "Expected to $action at least 1 $entity record, actually $actionPast none"
        );

        if ($model) {
            PHPUnit::assertContains(
                $model,
                $bucket,
                "The expected $entity was not $actionPast"
            );
        }
        
        return $this;
    }

    private function assertNoEntitiesActioned($action, $entity): self
    {
        $actionPast = $this->pastTense($action);

        /** @var array $bucket */
        $bucket = $this->{$actionPast.ucfirst($entity)."s"};

        PHPUnit::assertEmpty(
            $bucket,
            "Expected to $actionPast 0 $entity records, actually $actionPast " . count($bucket)
        );

        return $this;
    }

    private function pastTense(string $action): string
    {
        return $action.'d';
    }
}
