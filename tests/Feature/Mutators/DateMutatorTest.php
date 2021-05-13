<?php

namespace TheTreehouse\Relay\Tests\Feature\Mutators;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use TheTreehouse\Relay\Facades\Relay;
use TheTreehouse\Relay\Mutators\DateMutator;
use TheTreehouse\Relay\Tests\TestCase;

class DateMutatorTest extends TestCase
{
    public function test_it_is_auto_registered()
    {
        $this->assertEquals(DateMutator::class, Relay::resolveMutatorClass('date'));
    }

    public function test_it_passes_input_to_output_when_cannot_mutate_outbound()
    {
        $input = 'foo';

        $this->assertEquals(
            $input,
            $this->newDateMutator()->outbound($input)
        );
    }

    public function test_it_converts_outbound_carbon_to_date_string()
    {
        $carbon = CarbonImmutable::now();

        $this->assertEquals(
            $carbon->toDateString(),
            $this->newDateMutator()->outbound($carbon)
        );
    }

    public function test_it_passes_input_to_output_when_cannot_mutate_inbound()
    {
        $input = 'foo';

        $this->assertEquals(
            $input,
            $this->newDateMutator()->inbound($input)
        );
    }

    public function test_it_converts_inbound_date_string_to_carbon()
    {
        $date = '1999-12-30';

        $result = $this->newDateMutator()->inbound($date);

        $this->assertInstanceOf(Carbon::class, $result);
        $this->assertEquals($date, $result->toDateString());
    }

    private function newDateMutator(): DateMutator
    {
        return new DateMutator;
    }
}
