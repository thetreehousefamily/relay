<?php

namespace TheTreehouse\Relay\Exceptions;

use TheTreehouse\Relay\AbstractProvider;

class InvalidProviderException extends RelayException
{
    /**
     * Return a new InvalidProviderException instance from the provided invalid
     * class
     * 
     * @param string $class
     * @return static
     */
    public static function fromInvalidClass(string $class)
    {
        return new static("Cannot register Relay Provider: $class as it either does not exist or does not extend " . AbstractProvider::class);
    }
}