<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotificationWorkReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public object $jadwal;

    /**
     * Create a new job instance.
     */
    public function __construct($jadwal)
    {
        $this->jadwal = $jadwal;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        sendEmail([
            'to' => $this->jadwal->user->email,
            'subject' => 'Upload Bukti Kerja',
            'view' => 'work_report',
            'viewData' => [
                'jadwal' => $this->jadwal
            ]
        ]);
    }
}
