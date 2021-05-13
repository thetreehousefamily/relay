<?php

namespace TheTreehouse\Relay\Exceptions;

use TheTreehouse\Relay\AbstractProvider;

class PropertyException extends RelayException
{
    public static function invalidMutator($class)
    {
        return new static("Cannot register invalid mutator class `{$class}`");
    }

    public static function badMappingLocalKey(AbstractProvider $provider, $key = null)
    {
        return new static('Cannot use mapping key of type: '.gettype($key).' for provider: '.$provider->name());
    }

    public static function badMutator(AbstractProvider $provider, $mutator = null)
    {
        return new static(
            'Invalid mutator: '
            . (is_string($mutator) ? $mutator : (is_object($mutator) ? get_class($mutator) : gettype($mutator)))
            . ' used in mapping for provider: '
            . $provider->name()
        );
    }
}
