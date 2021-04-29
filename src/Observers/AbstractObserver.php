<?php

namespace TheTreehouse\Relay\Observers;

use Illuminate\Database\Eloquent\Model;
use TheTreehouse\Relay\Dispatcher;

abstract class AbstractObserver
{
    /**
     * The Relay Dispatcher instance
     * 
     * @var \TheTreehouse\Relay\Dispatcher
     */
    protected Dispatcher $dispatcher;

    /**
     * Instantiate the observer
     * 
     * @param \TheTreehouse\Relay\Dispatcher $dispatcher
     * @return void
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Relay the created model
     * 
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    abstract public function created(Model $model): void;

    /**
     * Relay the updated model
     * 
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    abstract public function updated(Model $model): void;

    /**
     * Relay the deleted model
     * 
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    abstract public function deleted(Model $model): void;
}