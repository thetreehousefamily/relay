<?php

namespace TheTreehouse\Relay\Tests\Feature\RelayEntityActionJob;

use TheTreehouse\Relay\Jobs\RelayEntityAction;
use TheTreehouse\Relay\Tests\TestCase;

abstract class BaseRelayEntityActionJobTest extends TestCase
{
    /** @var string */
    protected $entity;

    /** @var string */
    protected $entityModelClass;

    public function test_it_creates_entity()
    {
        $model = new $this->entityModelClass;

        $job = new RelayEntityAction(
            $model,
            $this->entity,
            RelayEntityAction::ACTION_CREATE,
            $this->fakeProvider()
        );

        $job->handle();

        $this->fakeProvider()->{"assert{$this->entityUcFirst()}Created"}($model);
    }

    public function test_it_updates_entity()
    {
        $model = new $this->entityModelClass;

        $job = new RelayEntityAction(
            $model,
            $this->entity,
            RelayEntityAction::ACTION_UPDATE,
            $this->fakeProvider()
        );

        $job->handle();

        $this->fakeProvider()->{"assert{$this->entityUcFirst()}Updated"}($model);
    }

    public function test_it_deletes_entity()
    {
        $model = new $this->entityModelClass;

        $job = new RelayEntityAction(
            $model,
            $this->entity,
            RelayEntityAction::ACTION_DELETE,
            $this->fakeProvider()
        );

        $job->handle();

        $this->fakeProvider()->{"assert{$this->entityUcFirst()}Deleted"}($model);
    }

    private function entityUcFirst(): string
    {
        return ucfirst($this->entity);
    }
}
