<?php

namespace TheTreehouse\Relay\Concerns\Provider;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use TheTreehouse\Relay\Support\Contracts\RelayJobContract;

trait GeneratesJobs
{
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
        return $this->newJob('create', 'contact', $contact);
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
        return $this->newJob('create', 'organization', $organization);
    }

    /**
     * Return a job instance that will update the provided contact on the
     * provider's service
     *
     * @param \Illuminate\Database\Eloquent\Model $contact
     * @return \TheTreehouse\Relay\Support\Contracts\RelayJobContract
     * @throws \TheTreehouse\Relay\Exceptions\ProviderSupportException Thrown if the provider does not support contacts
     */
    public function updateContactJob(Model $contact): RelayJobContract
    {
        return $this->newJob('update', 'contact', $contact);
    }

    /**
     * Return a job instance that will create the provided organization on the
     * provider's service
     *
     * @param \Illuminate\Database\Eloquent\Model $organization
     * @return \TheTreehouse\Relay\Support\Contracts\RelayJobContract
     * @throws \TheTreehouse\Relay\Exceptions\ProviderSupportException Thrown if the provider does not support organizations
     */
    public function updateOrganizationJob(Model $organization): RelayJobContract
    {
        return $this->newJob('update', 'organization', $organization);
    }

    /**
     * Return a job instance that will delete the provided contact on the
     * provider's service
     *
     * @param \Illuminate\Database\Eloquent\Model $contact
     * @return \TheTreehouse\Relay\Support\Contracts\RelayJobContract
     * @throws \TheTreehouse\Relay\Exceptions\ProviderSupportException Thrown if the provider does not support contacts
     */
    public function deleteContactJob(Model $contact): RelayJobContract
    {
        return $this->newJob('delete', 'contact', $contact);
    }

    /**
     * Return a job instance that will delete the provided organization on the
     * provider's service
     *
     * @param \Illuminate\Database\Eloquent\Model $organization
     * @return \TheTreehouse\Relay\Support\Contracts\RelayJobContract
     * @throws \TheTreehouse\Relay\Exceptions\ProviderSupportException Thrown if the provider does not support organizations
     */
    public function deleteOrganizationJob(Model $organization): RelayJobContract
    {
        return $this->newJob('delete', 'organization', $organization);
    }

    /**
     * Given an action and entity, instantiate and return the relevant job instance, guarding
     * for provider support.
     *
     * @param string $action
     * @param string $entity
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return \TheTreehouse\Relay\Support\Contracts\RelayJobContract
     */
    private function newJob(string $action, string $entity, Model $model): RelayJobContract
    {
        $entity === 'contact'
            ? $this->guardContactSupport()
            : $this->guardOrganizationSupport();

        $jobClassProperty = $action.ucfirst($entity).'Job';

        if (! $this->{$jobClassProperty}) {
            $this->{$jobClassProperty} = $this->guessJobName($action, $entity);
        }

        return app($this->{$jobClassProperty}, [$entity => $model]);
    }

    /**
     * Guess the job class name based on the provided entity and action
     *
     * @param string $action
     * @param string $entity
     * @return string
     */
    private function guessJobName(string $action, string $entity): string
    {
        return $this->guessNamespace()
            . '\\Jobs\\'
            . ucfirst($action)
            . Str::of($this->name())->studly()
            . ucfirst($entity);
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
