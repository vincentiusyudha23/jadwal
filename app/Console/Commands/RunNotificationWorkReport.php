<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Jadwal;
use Illuminate\Console\Command;
use App\Jobs\NotificationWorkReportJob;

class RunNotificationWorkReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-notification-work-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run notification work report';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Jadwal::where('tanggal', Carbon::now()->format('Y-m-d'))
            ->get()
            ->each(function ($jadwal) {
                if(now()->format('H:i') == '16:00'){
                    NotificationWorkReportJob::dispatch($jadwal, 'work');
                }

                $waktuJadwal = Carbon::parse($jadwal->waktu)->subHour();
                $satuJamSebelum = $waktuJadwal->format('H:i');

                if(now()->format('H:i') == $satuJamSebelum){
                    NotificationWorkReportJob::dispatch($jadwal, 'jadwal');
                }
            });
    }
}
