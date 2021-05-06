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
        return $this->processIncomingOperation('contact', 'created', $id, $properties);
    }

    /**
     * Process an incoming operation
     * 
     * @param string $entity
     * @param string $action
     * @param string $id
     * @param array $properties
     * @return mixed
     */
    protected function processIncomingOperation(string $entity, string $action, string $id, array $properties = [])
    {
        $supportMethod = "supports".ucfirst($entity)."s";

        if (!Relay::$supportMethod() || !$this->$supportMethod()) {
            return false;
        }

        $model;

        switch ($action) {
            case 'created':
                $model = $this->firstOrNewEntity($entity, $id)
                    ->fill(array_merge(
                        [$this->{$entity."ModelColumn"}() => $id],
                        $properties
                    ));

                $model->save();

                break;
        }

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