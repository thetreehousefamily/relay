<?php

namespace TheTreehouse\Relay\Exceptions;

use Illuminate\Database\Eloquent\Model;

class InvalidModelException extends RelayException
{
    /**
     * Return a new InvalidModelException instance from the provided invalid
     * class
     * 
     * @param string $class
     * @return static
     */
    public static function fromInvalidClass(string $class)
    {
        return new static("Cannot register model: $class as it either does not exist or does not extend " . Model::class);
    }
}