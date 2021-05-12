<?php

namespace TheTreehouse\Relay\Tests\Feature\Relayable;

use TheTreehouse\Relay\AbstractProvider;
use TheTreehouse\Relay\Exceptions\InvalidProviderException;
use TheTreehouse\Relay\Tests\Fixtures\Providers\FakeProvider;
use TheTreehouse\Relay\Tests\TestCase;

abstract class BaseRelayableTest extends TestCase
{
    /** @var string */
    protected $entityType;

    /** @var string */
    protected $modelClass;

    protected function relayProviders(): array
    {
        return [
            FakeProvider::class,
            ManualProvider::class,
        ];
    }

    public function test_it_rejects_invalid_provider()
    {
        $this->expectException(InvalidProviderException::class);

        $model = new $this->modelClass;

        $model->relay('Bad Provider');
    }

    public function test_it_relays_new_entity_on_demand()
    {
        $model = new $this->modelClass;

        $model->relay();

        $this->assertRelayEntityActionDispatched($model, $this->entity, 'create', FakeProvider::class);
        $this->assertRelayEntityActionDispatched($model, $this->entity, 'create', ManualProvider::class);
    }

    public function test_it_relays_new_entity_on_demand_to_single_provider()
    {
        $model = new $this->modelClass;

        $model->relay(ManualProvider::class);

        $this->assertRelayEntityActionDispatched($model, $this->entity, 'create', ManualProvider::class);

        $this->assertRelayEntityActionNotDispatched(FakeProvider::class);
    }

    public function test_it_relays_existing_entity_on_demand()
    {
        $model = new $this->modelClass([
            'fake_provider_id' => 'random-id',
            'manual_provider_id' => 'random-id'
        ]);

        $model->relay();

        $this->assertRelayEntityActionDispatched($model, $this->entity, 'update', FakeProvider::class);
        $this->assertRelayEntityActionDispatched($model, $this->entity, 'update', ManualProvider::class);
    }

    public function test_it_relays_existing_entity_on_demand_to_single_provider()
    {
        $model = new $this->modelClass([
            'manual_provider_id' => 'random-id',
        ]);

        $model->relay(ManualProvider::class);

        $this->assertRelayEntityActionDispatched($model, $this->entity, 'update', ManualProvider::class);

        $this->assertRelayEntityActionNotDispatched(FakeProvider::class);
    }
}

class ManualProvider extends AbstractProvider
{
    // ...
}
