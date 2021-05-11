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
            if ($accessorMethod = $this->generateModelMethodName('get', $modelKey)) {
                $properties[$providerKey] = $this->entity->{$accessorMethod}($this->entity->{$modelKey});

                continue;
            }

            $properties[$providerKey] = $this->entity->{$modelKey};
        }

        return $properties;
    }

    /**
     * Generate an array of properties mapped from the provider to the model
     *
     * @param array $inboundProperties
     * @return array
     */
    public function mapInbound(array $inboundProperties): array
    {
        $properties = [];

        foreach ($this->getMap() as $modelKey => $providerKey) {
            if (! isset($inboundProperties[$providerKey])) {
                continue;
            }

            $properties[$modelKey] = $inboundProperties[$providerKey];
        }

        return $properties;
    }

    /**
     * Calculate the inbound properties from the provided data, and set these
     * on the model
     *
     * @param array $inboundProperties
     * @return self
     */
    public function setInbound(array $inboundProperties): self
    {
        $inboundProperties = $this->mapInbound($inboundProperties);

        foreach ($inboundProperties as $modelKey => $value) {
            if ($mutatorMethod = $this->generateModelMethodName('set', $modelKey)) {
                $this->entity->{$mutatorMethod}($value);

                continue;
            }

            $this->entity->{$modelKey} = $value;
        }

        return $this;
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

    /**
     * Generate the accessor/mutator method names for the specific provider and
     * for relay generally
     *
     * @param string $action Either get or set
     * @param string $modelKey The standard form of the model key
     * @return string|null
     */
    private function generateModelMethodName(string $action, string $modelKey):? string
    {
        $baseAccessor = Str::of($modelKey)
                ->camel()
                ->ucfirst()
                ->prepend($action)
                ->append('AttributeFor');

        $providerMethod = (string) Str::of((string) $baseAccessor)
            ->append(
                Str::of($this->provider->name())
                    ->camel()
                    ->ucfirst()
            )
            ->append('Relay');
            
        $generalMethod = $baseAccessor.'Relay';

        if (method_exists($this->entity, $providerMethod)) {
            return $providerMethod;
        }

        if (method_exists($this->entity, $generalMethod)) {
            return $generalMethod;
        }

        return null;
    }
}
