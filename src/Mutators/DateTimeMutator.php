<?php

namespace TheTreehouse\Relay\Mutators;

use TheTreehouse\Relay\Mutators\Abstracts\AbstractDateMutator;

class DateTimeMutator extends AbstractDateMutator
{
    /**
     * Instantiate a new date mutator
     *
     * @param string $format
     * @return void
     */
    public function __construct(string $format = 'Y-m-d H:i:s')
    {
        $this->format = $format;
    }
}
