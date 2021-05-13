<?php

namespace TheTreehouse\Relay\Mutators;

use TheTreehouse\Relay\Mutators\Abstracts\AbstractDateMutator;

class DateMutator extends AbstractDateMutator
{
    /**
     * Instantiate a new date mutator
     *
     * @param string $format
     * @return void
     */
    public function __construct(string $format = 'Y-m-d')
    {
        $this->format = $format;
    }
}
