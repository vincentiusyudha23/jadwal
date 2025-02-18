<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SessionClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'for clear the session file - only for Dev';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        File::cleanDirectory(storage_path('framework/sessions'));
        $this->info('Session file berhasil dihapus.');
    }
}
