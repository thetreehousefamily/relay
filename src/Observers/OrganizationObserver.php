<?php

namespace TheTreehouse\Relay\Observers;

use Illuminate\Database\Eloquent\Model;

class OrganizationObserver extends AbstractObserver
{
    /** @inheritdoc */
    public function created(Model $model): void
    {
        $this->dispatcher->relayCreatedOrganization($model);
    }

    /** @inheritdoc */
    public function updated(Model $model): void
    {
        $this->dispatcher->relayUpdatedOrganization($model);
    }

    /** @inheritdoc */
    public function deleted(Model $model): void
    {
        $this->dispatcher->relayDeletedOrganization($model);
    }
}
