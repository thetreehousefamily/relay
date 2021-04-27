<?php

namespace TheTreehouse\Relay\Commands;

use Illuminate\Console\Command;

class RelayCommand extends Command
{
    public $signature = 'relay';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
