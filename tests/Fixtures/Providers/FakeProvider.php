<?php

namespace TheTreehouse\Relay\Tests\Fixtures\Providers;

use Illuminate\Testing\Assert as PHPUnit;
use TheTreehouse\Relay\AbstractProvider;

class FakeProvider extends AbstractProvider
{
    /**
     * The contact records that would have been created
     * 
     * @var \Illuminate\Database\Eloquent\Model[]
     */
    protected array $createdContacts = [];

    /**
     * Assert that the provider was not asked to create a contact
     * 
     * @return static
     */
    public function assertNoContactsCreated(): self
    {
        PHPUnit::assertEmpty($this->createdContacts, 'Expected to create 0 contact records, actually created ' . count($this->createdContacts));

        return $this;
    }
}
