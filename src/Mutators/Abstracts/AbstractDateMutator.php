<?php

namespace TheTreehouse\Relay\Mutators\Abstracts;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\Exceptions\InvalidFormatException;
use TheTreehouse\Relay\Support\Contracts\MutatorContract;

abstract class AbstractDateMutator implements MutatorContract
{
    /**
     * The format to mutate to/from
     *
     * @var string
     */
    protected $format;

    /**
     * Attempt to convert the Carbon $value to the desired format
     *
     * @param mixed $value
     * @return mixed
     */
    public function outbound($value)
    {
        if ($value instanceof CarbonInterface) {
            return $value->format($this->format);
        }

        return $value;
    }

    /**
     * Attempt to parse a formatted date string into a Carbon instance
     *
     * @param mixed $value
     * @return mixed
     */
    public function inbound($value)
    {
        try {
            $carbon = Carbon::createFromFormat($this->format, $value);
        } catch (InvalidFormatException $exception) {
            return $value;
        }

        return $carbon;
    }
}
