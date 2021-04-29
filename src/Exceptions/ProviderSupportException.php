<?php

namespace TheTreehouse\Relay\Exceptions;

use TheTreehouse\Relay\AbstractProvider;

class ProviderSupportException extends RelayException
{
    public static function contactsNotSupported(AbstractProvider $provider): self
    {
        return self::typeNotSupported($provider, 'contacts');
    }

    public static function organizationsNotSupported(AbstractProvider $provider): self
    {
        return self::typeNotSupported($provider, 'organizations');
    }

    private static function typeNotSupported(AbstractProvider $provider, string $type): self
    {
        return new static(ucfirst($type) . ' are not supported by the ' . $provider->name() . ' provider.');
    }
}