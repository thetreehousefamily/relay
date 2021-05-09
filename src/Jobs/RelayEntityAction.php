<?php

namespace TheTreehouse\Relay\Jobs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use TheTreehouse\Relay\AbstractProvider;

class RelayEntityAction
{
    use Dispatchable;

    public const ENTITY_CONTACT = 'contact';

    public const ENTITY_ORGANIZATION = 'organization';

    public const ACTION_CREATE = 'create';

    public const ACTION_UPDATE = 'update';

    public const ACTION_DELETE = 'delete';

    /**
     * The entity to relay
     * 
     * @var \Illuminate\Eloquent\Model
     */
    public $entity;

    /**
     * The type of entity being relayed
     * 
     * @var string
     */
    public $entityType;

    /**
     * The action being relayed
     * 
     * @var string
     */
    public $action;

    /**
     * The provider class to relay to
     * 
     * @var string
     */
    public $provider;

    /**
     * Instantiate a new Relay Job
     * 
     * @param \Illuminate\Eloquent\Model $entity
     * @param string $entityType One of ENTITY_CONTACT or ENTITY_ORGANIZATION
     * @param string $action One of ACTION_CREATE, ACTION_UPDATE or ACTION_DELETE
     * @param \TheTreehouse\Relay\AbstractProvider $provider
     * @return void
     */
    public function __construct(Model $entity, string $entityType, string $action, AbstractProvider $provider)
    {
        $this->entity = $entity;
        $this->entityType = $entityType;
        $this->action = $action;
        $this->provider = get_class($provider);
    }
}