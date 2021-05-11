<?php

namespace TheTreehouse\Relay;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PropertyMapper
{
    public const ENTITY_CONTACT = 'contact';

    public const ENTITY_ORGANIZATION = 'organization';

    /**
     * The entity to map to/from
     * 
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $entity;

    /**
     * The entity type, either 'contact' or 'organization'
     * 
     * @var string
     */
    protected $entityType;
    
    /**
     * The provider to map to/from
     * 
     * @var \TheTreehouse\Relay\AbstractProvider
     */
    protected $provider;

    /**
     * Instantiate a new mapper instance
     * 
     * @param \Illuminate\Database\Eloquent\Model $entity
     * @param string $entityType
     * @param \TheTreehouse\Relay\AbstractProvider $provider
     * @return void
     */
    public function __construct(Model $entity, string $entityType, AbstractProvider $abstractProvider)
    {
        $this->entity = $entity;
        $this->entityType = $entityType;
        $this->provider = $abstractProvider;
    }

    /**
     * Generate an array of properties mapped from the model, to the provider
     * 
     * @return array
     */
    public function mapOutbound(): array
    {
        $properties = [];

        foreach ($this->getMap() as $modelKey => $providerKey) {
            // Check to see if there's a specific accessor for this provider, or
            // if there's a specific relay accessor.
            $baseAccessor = Str::of($modelKey)
                ->camel()
                ->ucfirst()
                ->prepend('get')
                ->append('AttributeFor');

            $providerAccessor = (string) Str::of($baseAccessor)
                ->append(
                    Str::of($this->provider->name())
                        ->camel()
                        ->ucfirst()
                )
                ->append('Relay');
                
            $generalAccessor = $baseAccessor.'Relay';

            $accessorMethod =
                    method_exists($this->entity, $providerAccessor)
                    ? $providerAccessor
                    : (
                        method_exists($this->entity, $generalAccessor)
                        ? $generalAccessor
                        : null
                    );

            if (method_exists($this->entity, $accessorMethod)) {
                $properties[$providerKey] = $this->entity->{(string) $accessorMethod}($this->entity->{$modelKey});

                continue;
            }

            // Otherwise, retrieve value normally
            $properties[$providerKey] = $this->entity->{$modelKey};
        }

        return $properties;
    }

    /**
     * Retrieve the map from the configuration
     * 
     * @return array
     */
    private function getMap(): array
    {
        return config(
            $this->provider->configKey()
            .".{$this->entityType}_fields",
            []
        );
    }
}
