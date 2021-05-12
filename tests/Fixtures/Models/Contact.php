<?php

namespace TheTreehouse\Relay\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use TheTreehouse\Relay\Concerns\Relayable;

class Contact extends Model
{
    use Relayable;
    
    protected $guarded = [];
}
