<?php

namespace TheTreehouse\Relay\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use TheTreehouse\Relay\Concerns\Relayable;

class Organization extends Model
{
    use Relayable;
    
    protected $guarded = [];
}
