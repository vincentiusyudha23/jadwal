<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Jobs\NotificationAbsenJob;

class RunNotificationUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-notification-user {--type=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Schedule notification to user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type') ?? 'masuk';

        User::hasKaryawan()->each(function ($user) use ($type) {
            NotificationAbsenJob::dispatch($user, $type);
        });
    }
}
