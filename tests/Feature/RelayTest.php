<?php

namespace TheTreehouse\Relay\Tests\Feature;

use TheTreehouse\Relay\Exceptions\PropertyException;
use TheTreehouse\Relay\Facades\Relay;
use TheTreehouse\Relay\Support\Contracts\MutatorContract;
use TheTreehouse\Relay\Tests\Fixtures\Models\Contact;
use TheTreehouse\Relay\Tests\Fixtures\Models\Organization;
use TheTreehouse\Relay\Tests\TestCase;

class RelayTest extends TestCase
{
    public function configureRelay()
    {
        if ($this->getName() !== 'test_it_uses_empty_model_configuration') {
            return parent::configureRelay();
        }
    }
    
    public function test_it_uses_empty_model_configuration()
    {
        $this->assertNull(Relay::contactModel());
        $this->assertNull(Relay::organizationModel());
    }

    public function test_it_uses_model_configuration()
    {
        $this->assertEquals(Contact::class, Relay::contactModel());
        $this->assertEquals(Organization::class, Relay::organizationModel());
    }

    public function test_it_rejects_invalid_mutators()
    {
        $this->expectException(PropertyException::class);

        Relay::registerMutator(get_class(new \stdClass), 'foo');
    }

    public function test_it_registers_mutator()
    {
        Relay::registerMutator(ExampleValidMutator::class, 'example_mutator');

        $this->assertInstanceOf(ExampleValidMutator::class, Relay::getMutator(ExampleValidMutator::class));
        $this->assertInstanceOf(ExampleValidMutator::class, Relay::getMutator('example_mutator'));
    }

    public function test_it_returns_null_for_invalid_mutator()
    {
        $this->assertNull(Relay::getMutator(12345));
    }

    public function test_it_returns_same_mutator()
    {
        Relay::registerMutator(ExampleValidMutator::class);

        $instance = new ExampleValidMutator;

        $this->assertSame(
            $instance,
            Relay::getMutator($instance)
        );
    }
}

class ExampleValidMutator implements MutatorContract
{
    public function outbound($value)
    {
        return $value;
    }

    public function inbound($value)
    {
        return $value;
    }
}
