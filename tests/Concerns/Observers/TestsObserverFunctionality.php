<?php

namespace TheTreehouse\Relay\Tests\Concerns\Observers;

use Closure;
use Illuminate\Events\Dispatcher;
use Opis\Closure\ReflectionClosure;

trait TestsObserverFunctionality
{
    protected function getDispatcher(): Dispatcher
    {
        return $this->app->make(Dispatcher::class);
    }

    protected function assertListenersIncludeClassListener(array $listeners, string $classListener)
    {
        foreach ($listeners as $listener) {
            if (! $listener instanceof Closure) {
                continue;
            }

            $reflection = new ReflectionClosure($listener);

            if (! isset(($useVariables = $reflection->getUseVariables())['listener'])) {
                continue;
            }

            if ($useVariables['listener'] === $classListener) {
                return $this->assertTrue(true);
            }
        }

        $this->fail("Provided listeners does not include class listener: $classListener");
    }
}
