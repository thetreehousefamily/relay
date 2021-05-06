<?php

namespace TheTreehouse\Relay\Tests\Feature\AbstractProvider;

use Illuminate\Contracts\Container\BindingResolutionException;
use TheTreehouse\Relay\Exceptions\ProviderSupportException;
use TheTreehouse\Relay\Tests\Fixtures\Models\Contact;
use TheTreehouse\Relay\Tests\Fixtures\Models\Organization;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs\CreateFakeContact;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs\CreateFakeOrganization;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs\DeleteFakeContact;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs\DeleteFakeOrganization;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs\UpdateFakeContact;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs\UpdateFakeOrganization;

class GeneratesJobsTest extends BaseAbstractProviderTest
{
    public function test_it_throws_exception_when_generating_create_contact_job_and_contacts_are_not_supported()
    {
        $provider = $this->newAbstractProviderImplementation();

        $provider->supportsContacts = false;

        $this->expectException(ProviderSupportException::class);

        $provider->createContactJob(new Contact);
    }

    public function test_it_throws_exception_when_generating_create_organization_job_and_organizations_are_not_supported()
    {
        $provider = $this->newAbstractProviderImplementation();

        $provider->supportsOrganizations = false;

        $this->expectException(ProviderSupportException::class);

        $provider->createOrganizationJob(new Organization);
    }

    public function test_it_generates_predefined_create_contact_job()
    {
        $provider = $this->newAbstractProviderImplementation();

        $provider->createContactJob = CreateFakeContact::class;

        $job = $provider->createContactJob(new Contact);

        $this->assertInstanceOf(CreateFakeContact::class, $job);
    }

    public function test_it_correctly_guesses_create_contact_job()
    {
        $provider = $this->newAbstractProviderImplementation();

        try {
            $message = "";

            $provider->createContactJob(new Contact);
        } catch (BindingResolutionException $exception) {
            $message = $exception->getMessage();
        }

        $this->assertStringContainsString(
            'TheTreehouse\\Relay\\Tests\\Feature\\AbstractProvider\\Jobs\\CreateHubFakeContact',
            $message
        );
    }

    public function test_it_generates_predefined_create_organization_job()
    {
        $provider = $this->newAbstractProviderImplementation();

        $provider->createOrganizationJob = CreateFakeOrganization::class;

        $job = $provider->createOrganizationJob(new Organization);

        $this->assertInstanceOf(CreateFakeOrganization::class, $job);
    }

    public function test_it_correctly_guesses_create_organization_job()
    {
        $provider = $this->newAbstractProviderImplementation();

        try {
            $message = "";

            $provider->createOrganizationJob(new Organization);
        } catch (BindingResolutionException $exception) {
            $message = $exception->getMessage();
        }

        $this->assertStringContainsString(
            'TheTreehouse\\Relay\\Tests\\Feature\\AbstractProvider\\Jobs\\CreateHubFakeOrganization',
            $message
        );
    }

    public function test_it_generates_predefined_update_contact_job()
    {
        $provider = $this->newAbstractProviderImplementation();

        $provider->updateContactJob = UpdateFakeContact::class;

        $job = $provider->updateContactJob(new Contact);

        $this->assertInstanceOf(UpdateFakeContact::class, $job);
    }

    public function test_it_correctly_guesses_update_contact_job()
    {
        $provider = $this->newAbstractProviderImplementation();

        try {
            $message = "";

            $provider->updateContactJob(new Contact);
        } catch (BindingResolutionException $exception) {
            $message = $exception->getMessage();
        }

        $this->assertStringContainsString(
            'TheTreehouse\\Relay\\Tests\\Feature\\AbstractProvider\\Jobs\\UpdateHubFakeContact',
            $message
        );
    }

    public function test_it_generates_predefined_update_organization_job()
    {
        $provider = $this->newAbstractProviderImplementation();

        $provider->updateOrganizationJob = UpdateFakeOrganization::class;

        $job = $provider->updateOrganizationJob(new Organization);

        $this->assertInstanceOf(UpdateFakeOrganization::class, $job);
    }

    public function test_it_correctly_guesses_update_organization_job()
    {
        $provider = $this->newAbstractProviderImplementation();

        try {
            $message = "";

            $provider->updateOrganizationJob(new Organization);
        } catch (BindingResolutionException $exception) {
            $message = $exception->getMessage();
        }

        $this->assertStringContainsString(
            'TheTreehouse\\Relay\\Tests\\Feature\\AbstractProvider\\Jobs\\UpdateHubFakeOrganization',
            $message
        );
    }

    public function test_it_generates_predefined_delete_contact_job()
    {
        $provider = $this->newAbstractProviderImplementation();

        $provider->deleteContactJob = DeleteFakeContact::class;

        $job = $provider->deleteContactJob(new Contact);

        $this->assertInstanceOf(DeleteFakeContact::class, $job);
    }

    public function test_it_correctly_guesses_delete_contact_job()
    {
        $provider = $this->newAbstractProviderImplementation();

        try {
            $message = "";

            $provider->deleteContactJob(new Contact);
        } catch (BindingResolutionException $exception) {
            $message = $exception->getMessage();
        }

        $this->assertStringContainsString(
            'TheTreehouse\\Relay\\Tests\\Feature\\AbstractProvider\\Jobs\\DeleteHubFakeContact',
            $message
        );
    }

    public function test_it_generates_predefined_delete_organization_job()
    {
        $provider = $this->newAbstractProviderImplementation();

        $provider->deleteOrganizationJob = DeleteFakeOrganization::class;

        $job = $provider->deleteOrganizationJob(new Organization);

        $this->assertInstanceOf(DeleteFakeOrganization::class, $job);
    }

    public function test_it_correctly_guesses_delete_organization_job()
    {
        $provider = $this->newAbstractProviderImplementation();

        try {
            $message = "";

            $provider->deleteOrganizationJob(new Organization);
        } catch (BindingResolutionException $exception) {
            $message = $exception->getMessage();
        }

        $this->assertStringContainsString(
            'TheTreehouse\\Relay\\Tests\\Feature\\AbstractProvider\\Jobs\\DeleteHubFakeOrganization',
            $message
        );
    }
}
