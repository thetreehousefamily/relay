<?php

namespace TheTreehouse\Relay\Support;

use TheTreehouse\Relay\Relay;

class FakeRelay
{
    /**
     * The original Relay singleton instance
     * 
     * @var \TheTreehouse\Relay\Relay
     */
    protected $relay;

    /**
     * Override fake value for supports contacts
     * 
     * @var bool|null
     */
    protected bool $supportsContacts;

    /**
     * Instantiate the Fake Relay with the original instance, so that calls can be
     * proxied if not faked
     * 
     * @param \TheTreehouse\Relay\Relay $relay
     * @return void
     */
    public function __construct(Relay $relay)
    {
        $this->relay = $relay;
    }

    /**
     * Manually set the supports contacts value
     * 
     * @param bool $supportsContacts
     * @return static
     */
    public function setSupportsContacts(bool $supportsContacts): self
    {
        $this->supportsContacts = $supportsContacts;

        return $this;
    }

    /**
     * Override parent supportsContacts
     * 
     * @return bool
     */
    public function supportsContacts(): bool
    {
        if ($this->supportsContacts === null) {
            return $this->relay->supportsContacts();
        }

        return $this->supportsContacts;
    }
}