<?php

namespace TheTreehouse\Relay\Tests\Feature\Mutators;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use TheTreehouse\Relay\Facades\Relay;
use TheTreehouse\Relay\Mutators\DateTimeMutator;
use TheTreehouse\Relay\Tests\TestCase;

class DateTimeMutatorTest extends TestCase
{
    public function test_it_is_auto_registered()
    {
        $this->assertInstanceOf(DateTimeMutator::class, Relay::getMutator('datetime'));
    }

    public function test_it_passes_input_to_output_when_cannot_mutate_outbound()
    {
        $input = 'foo';

        $this->assertEquals(
            $input,
            $this->newDateTimeMutator()->outbound($input)
        );
    }

    public function test_it_converts_outbound_carbon_to_date_string()
    {
        $carbon = CarbonImmutable::now();

        $this->assertEquals(
            $carbon->toDateTimeString(),
            $this->newDateTimeMutator()->outbound($carbon)
        );
    }

    public function test_it_passes_input_to_output_when_cannot_mutate_inbound()
    {
        $input = 'foo';

        $this->assertEquals(
            $input,
            $this->newDateTimeMutator()->inbound($input)
        );
    }

    public function test_it_converts_inbound_date_string_to_carbon()
    {
        $date = '1999-12-30 01:23:45';

        $result = $this->newDateTimeMutator()->inbound($date);

        $this->assertInstanceOf(Carbon::class, $result);
        $this->assertEquals($date, $result->toDateTimeString());
    }

    private function newDateTimeMutator(): DateTimeMutator
    {
        return new DateTimeMutator;
    }
}
