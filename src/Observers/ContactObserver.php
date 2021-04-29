<?php

namespace TheTreehouse\Relay\Observers;

use Illuminate\Database\Eloquent\Model;

class ContactObserver extends AbstractObserver
{
    /** @inheritdoc */
    public function created(Model $model): void
    {
        $this->dispatcher->relayCreatedContact($model);
    }

    /** @inheritdoc */
    public function updated(Model $model): void
    {
        $this->dispatcher->relayUpdatedContact($model);
    }

    /** @inheritdoc */
    public function deleted(Model $model): void
    {
        $this->dispatcher->relayDeletedContact($model);
    }
}
