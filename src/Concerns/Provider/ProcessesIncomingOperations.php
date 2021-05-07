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
     * Relay an organization that was created on this provider to the rest of the application
     * 
     * @param string $id 
     * @param array $properties 
     * @return \Illuminate\Database\Eloquent\Model|bool The created model, or false if the organization was not otherwise processed
     */
    public function createdOrganization(string $id, array $properties)
    {
        return $this->processIncomingOperation('organization', 'created', $id, $properties);
    }
    
    /**
     * Relay a contact that was updated on this provider to the rest of the application
     * 
     * @param string $id 
     * @param array $properties 
     * @return \Illuminate\Database\Eloquent\Model|bool The updated model, or false if the contact was not otherwise processed
     */
    public function updatedContact(string $id, array $properties)
    {
        return $this->processIncomingOperation('contact', 'updated', $id, $properties);
    }

    /**
     * Relay an organization that was updated on this provider to the rest of the application
     * 
     * @param string $id 
     * @param array $properties 
     * @return \Illuminate\Database\Eloquent\Model|bool The updated model, or false if the organization was not otherwise processed
     */
    public function updatedOrganization(string $id, array $properties)
    {
        return $this->processIncomingOperation('organization', 'updated', $id, $properties);
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

        if ($action === 'created' || $action === 'updated') {
            $model = $this->firstOrNewEntity($entity, $id)
                ->fill(array_merge(
                    [$this->{$entity."ModelColumn"}() => $id],
                    $properties
                ));
    
            $model->save();

            return $model;
        }
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