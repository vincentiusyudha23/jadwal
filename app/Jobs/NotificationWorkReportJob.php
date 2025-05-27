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
    public $type;

    /**
     * Create a new job instance.
     */
    public function __construct($jadwal, $type)
    {
        $this->jadwal = $jadwal;
        $this->type = $type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $type = $this->type;
        if($type == 'work'){
            $this->notifyWorkReport();
        }
        if($type == 'jadwal'){
            $this->notifyJadwal();
        }
    }

    public function notifyWorkReport()
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

    public function notifyJadwal()
    {
        sendEmail([
            'to' => $this->jadwal->user->email,
            'subject' => 'Notifikasi Jadwal!',
            'view' => 'jadwal',
            'viewData' => [
                'jadwal' => $this->jadwal,
                'msg_jadwal' => 'Notifikasi jadwal untuk hari ini.'
            ]
        ]);
    }
}
