<?php

namespace TheTreehouse\Relay\Tests\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Bus;
use TheTreehouse\Relay\Jobs\RelayEntityAction;

trait AssertsAgainstRelayEntityActionJob
{
    public function assertRelayEntityActionNotDispatched(string $provider = null)
    {
        if (!$provider) {
            Bus::assertNotDispatched(RelayEntityAction::class);

            return $this;
        }

        Bus::assertNotDispatched(RelayEntityAction::class, function (RelayEntityAction $job) use ($provider) {
            return $job->provider === $provider;
        });

        return $this;
    }

    public function assertRelayEntityActionDispatched(Model $entity, string $entityType, string $action, string $provider)
    {
        Bus::assertDispatched(RelayEntityAction::class, function (RelayEntityAction $job) use ($entity, $entityType, $action, $provider) {
            if (
                $job->entity !== $entity
                || $job->entityType !== strtolower($entityType)
                || $job->action !== $action
                || $job->provider !== $provider
            ) {
                return false;
            }

            return true;
        });

        return $this;
    }
}
