<?php

namespace TheTreehouse\Relay\Support\Contracts;

interface MutatorContract
{
    /**
     * Format the provided value for outbound processing
     * 
     * @param mixed $value
     * @return mixed
     */
    public function outbound($value);

    /**
     * Format the provided value for inbound persistence
     * 
     * @param mixed $value
     * @return mixed
     */
    public function inbound($value);
}
