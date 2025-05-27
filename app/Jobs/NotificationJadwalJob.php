<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotificationJadwalJob implements ShouldQueue
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
            'subject' => 'Jadwal Pekerjaan Terbaru!',
            'view' => 'jadwal',
            'viewData' => [
                'jadwal' => $this->jadwal,
                'msg_jadwal' => 'Anda memiliki jadwal pekerjaan yang baru saja dibuat.'
            ]
        ]);
    }
}
