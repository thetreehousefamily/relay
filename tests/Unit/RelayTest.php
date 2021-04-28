<?php

namespace TheTreehouse\Relay\Tests\Unit;

use PHPUnit\Framework\TestCase;
use TheTreehouse\Relay\AbstractProvider;
use TheTreehouse\Relay\Exceptions\InvalidProviderException;
use TheTreehouse\Relay\Relay;

class RelayTest extends TestCase
{
    public function test_it_rejects_invalid_registrations()
    {
        $invalidProvider = new class {};

        $this->expectException(InvalidProviderException::class);

        Relay::registerProvider(get_class($invalidProvider));
    }

    public function test_it_registers_valid_providers()
    {
        $validProvider = $this->createMock(AbstractProvider::class);

        Relay::registerProvider(get_class($validProvider));

        $this->assertContains(get_class($validProvider), Relay::getRegisteredProviders());
    }
}