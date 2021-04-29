<?php

namespace TheTreehouse\Relay\Tests\Feature;

use Illuminate\Contracts\Container\BindingResolutionException;
use TheTreehouse\Relay\AbstractProvider;
use TheTreehouse\Relay\Exceptions\ProviderSupportException;
use TheTreehouse\Relay\Tests\Fixtures\Models\Contact;
use TheTreehouse\Relay\Tests\Fixtures\Models\Organization;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs\CreateFakeContact;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider\Jobs\CreateFakeOrganization;
use TheTreehouse\Relay\Tests\TestCase;

class AbstractProviderTest extends TestCase
{
    public function test_it_returns_predefined_name()
    {
        $provider = $this->newAbstractProviderImplementation();

        $provider->name = 'Predefined Name';

        $this->assertEquals('Predefined Name', $provider->name());
    }

    public function test_it_correctly_guesses_name()
    {
        $provider = $this->newAbstractProviderImplementation();

        $this->assertEquals('Abstract Provider Implementation', $provider->name());
    }

    public function test_it_returns_predefined_contact_model_column()
    {
        $provider = $this->newAbstractProviderImplementation();

        $provider->contactModelColumn = 'predefined_contact_model_column';

        $this->assertEquals('predefined_contact_model_column', $provider->contactModelColumn());
    }

    public function test_it_correctly_guesses_contact_model_column()
    {
        $provider = $this->newAbstractProviderImplementation();

        $this->assertEquals('abstract_provider_implementation_id', $provider->contactModelColumn());
    }

    public function test_it_returns_predefined_organization_model_column()
    {
        $provider = $this->newAbstractProviderImplementation();

        $provider->organizationModelColumn = 'predefined_organization_model_column';

        $this->assertEquals('predefined_organization_model_column', $provider->organizationModelColumn());
    }

    public function test_it_correctly_guesses_organization_model_column()
    {
        $provider = $this->newAbstractProviderImplementation();

        $this->assertEquals('abstract_provider_implementation_id', $provider->organizationModelColumn());
    }

    public function test_it_throws_exception_when_checking_contact_exists_and_contacts_are_not_supported()
    {
        $provider = $this->newAbstractProviderImplementation();

        $provider->supportsContacts = false;

        $this->expectException(ProviderSupportException::class);

        $provider->contactExists(new Contact);
    }

    public function test_it_throws_exception_when_checking_organization_exists_and_organizations_are_not_supported()
    {
        $provider = $this->newAbstractProviderImplementation();

        $provider->supportsOrganizations = false;

        $this->expectException(ProviderSupportException::class);

        $provider->organizationExists(new Organization);
    }

    public function test_it_throws_exception_when_getting_contact_model_column_and_contacts_are_not_supported()
    {
        $provider = $this->newAbstractProviderImplementation();

        $provider->supportsContacts = false;

        $this->expectException(ProviderSupportException::class);

        $provider->contactModelColumn();
    }

    public function test_it_throws_exception_when_getting_organization_model_column_and_organizations_are_not_supported()
    {
        $provider = $this->newAbstractProviderImplementation();

        $provider->supportsOrganizations = false;

        $this->expectException(ProviderSupportException::class);

        $provider->organizationModelColumn();
    }

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
            'TheTreehouse\\Relay\\Tests\\Feature\\Jobs\\CreateAbstractProviderImplementationContact',
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
            'TheTreehouse\\Relay\\Tests\\Feature\\Jobs\\CreateAbstractProviderImplementationOrganization',
            $message
        );
    }

    private function newAbstractProviderImplementation(): AbstractProvider
    {
        return new AbstractProviderImplementationRelay;
    }
}

class AbstractProviderImplementationRelay extends AbstractProvider
{
    // Increase the visibility of the properties for testing
    public $name;
    public $supportsContacts = true;
    public $supportsOrganizations = true;
    public $contactModelColumn;
    public $organizationModelColumn;
    public $createContactJob;
    public $createOrganizationJob;
}
