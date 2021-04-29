<?php

namespace TheTreehouse\Relay\Tests\Feature\Observers;

use Closure;
use Illuminate\Events\Dispatcher as LaravelDispatcher;
use ReflectionFunction;
use TheTreehouse\Relay\Observers\AbstractObserver;
use TheTreehouse\Relay\Dispatcher;
use TheTreehouse\Relay\Tests\TestCase;

abstract class BaseObserverTest extends TestCase
{
    /**
     * The string name of the entity, either 'Contact' or 'Organization'
     * 
     * @var string
     */
    protected $entityName;

    /**
     * The class name of the observable model, either Contact::class or Organization::class
     * 
     * @var string
     */
    protected $observableModel;

    /**
     * The class name of the subject observer class, either ContactObserver::class or OrganizationObserver::class
     * 
     * @var string
     */
    protected $observer;

    protected function configureRelay()
    {
        if ($this->getName() !== 'test_it_doesnt_observe_model_events') {
            return parent::configureRelay();
        }
    }

    public function test_it_observes_model_events()
    {
        $dispatcher = $this->getLaravelDispatcher();

        $this->assertListenersIncludeClassListener(
            $dispatcher->getListeners("eloquent.created: " . $this->observableModel),
            $this->observer . "@created"
        );

        $this->assertListenersIncludeClassListener(
            $dispatcher->getListeners("eloquent.updated: " . $this->observableModel),
            $this->observer . "@updated"
        );

        $this->assertListenersIncludeClassListener(
            $dispatcher->getListeners("eloquent.deleted: " . $this->observableModel),
            $this->observer . "@deleted"
        );
    }

    public function test_it_doesnt_observe_model_events()
    {
        $dispatcher = $this->getLaravelDispatcher();

        $this->assertListenersDoesntIncludeClassListener(
            $dispatcher->getListeners("eloquent.created: " . $this->observableModel),
            $this->observer . "@created"
        );

        $this->assertListenersDoesntIncludeClassListener(
            $dispatcher->getListeners("eloquent.updated: " . $this->observableModel),
            $this->observer . "@updated"
        );

        $this->assertListenersDoesntIncludeClassListener(
            $dispatcher->getListeners("eloquent.deleted: " . $this->observableModel),
            $this->observer . "@deleted"
        );
    }

    public function test_it_relays_events_to_dispatcher()
    {
        $model = new $this->observableModel;

        $dispatcher = $this->createMock(Dispatcher::class);

        $dispatcher->expects($this->once())
            ->method('relayCreated' . $this->entityName)
            ->with($model)
            ->willReturnSelf();

        $dispatcher->expects($this->once())
            ->method('relayUpdated' . $this->entityName)
            ->with($model)
            ->willReturnSelf();

        $dispatcher->expects($this->once())
            ->method('relayDeleted' . $this->entityName)
            ->with($model)
            ->willReturnSelf();

        $observer = $this->newObserver($dispatcher);

        $observer->created($model);
        $observer->updated($model);
        $observer->deleted($model);
    }

    protected function getLaravelDispatcher(): LaravelDispatcher
    {
        return $this->app->make(LaravelDispatcher::class);
    }

    protected function assertListenersIncludeClassListener(array $listeners, string $classListener)
    {
        $this->assertAgainstClassListener($listeners, $classListener, true);
    }

    protected function assertListenersDoesntIncludeClassListener(array $listeners, string $classListener)
    {
        $this->assertAgainstClassListener($listeners, $classListener, false);
    }

    private function assertAgainstClassListener(array $listeners, string $classListener, bool $assertIncludes)
    {
        foreach ($listeners as $listener) {
            if (! $listener instanceof Closure) {
                continue;
            }

            $reflection = new ReflectionFunction($listener);

            if (! isset(($useVariables = $reflection->getStaticVariables())['listener'])) {
                continue;
            }

            if ($useVariables['listener'] === $classListener) {
                if (!$assertIncludes) {
                    $this->fail("Provided listeners includes unexpected class listener: $classListener");
                }
                
                return $this->assertTrue(true);
            }
        }

        if ($assertIncludes) {
            $this->fail("Provided listeners does not include class listener: $classListener");
        }

        return $this->assertTrue(true);
    }

    /**
     * Instantiate a new observer instance with the provided dispatcher, or resolve the dispatcher
     * instance from the service container if none provided
     * 
     * @param \TheTreehouse\Relay\Dispatcher|\PHPUnit\Framework\MockObject\MockObject|null
     * @return \TheTreehouse\Relay\Observers\AbstractObserver
     */
    protected function newObserver($dispatcher = null): AbstractObserver
    {
        if (!$dispatcher) {
            /** @var \TheTreehouse\Relay\Dispatcher $dispatcher */
            $dispatcher = $this->app->make('relay.dispatcher');
        }

        $className = "TheTreehouse\\Relay\\Observers\\{$this->entityName}Observer";

        /** @var \TheTreehouse\Relay\Observers\AbstractObserver $observer */
        $observer = new $className($dispatcher);

        return $observer;
    }
}
