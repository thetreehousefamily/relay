<?php

namespace TheTreehouse\Relay;

use Illuminate\Support\Facades\Facade;

/**
 * @see \TheTreehouse\Relay\Relay
 */
class RelayFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'relay';
    }
}
