<?php

namespace TheTreehouse\Relay\Concerns\Provider;

use Illuminate\Database\Eloquent\Model;
use TheTreehouse\Relay\Facades\Relay;

trait ProcessesIncomingOperations
{
    /**
     * Relay a contact that was created on this provider to the rest of the application
     * 
     * @param string $id 
     * @param array $properties 
     * @return \Illuminate\Database\Eloquent\Model|bool The created model, or false if the contact was not otherwise processed
     */
    public function createdContact(string $id, array $properties)
    {
        if (!Relay::supportsContacts() || !$this->supportsContacts()) {
            return false;
        }

        $model = $this->firstOrNewEntity('contact', $id);

        $model->fill(array_merge([$this->contactModelColumn() => $id], $properties));

        $model->save();

        return $model;
    }

    /**
     * Retrieve an existing model instance by the provided entity type and id, or a new
     * model if one does not yet exist
     * 
     * @param string $entity
     * @param string $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function firstOrNewEntity(string $entity, string $id): Model
    {
        $modelClass = Relay::{$entity."Model"}();
        $modelColumn = $this->{$entity."ModelColumn"}();

        return $modelClass::firstOrNew([$modelColumn => $id]);
    }
}