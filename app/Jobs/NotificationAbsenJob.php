<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotificationAbsenJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public object $user;
    private string $route;
    public string $type;
    /**
     * Create a new job instance.
     */
    public function __construct($user, $type)
    {
        $this->user = $user;
        $this->route = route('karyawan.absen.view');
        $this->type = $type == 'masuk' ? 'Masuk' : 'Pulang';
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->sendEmail();
    }

    public function sendEmail()
    {
        try {
            sendEmail([
                'to' => $this->user->email,
                'subject' => 'Pengingat Absen ' . $this->type,
                'view' => 'notifabsen',
                'viewData' => [
                    'subject' => 'Pengingat Absen ' . $this->type,
                    'nama_karyawan' => $this->user->name,
                    'link_absen' => $this->route,
                    'type' => $this->type
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error sending email: ' . $e->getMessage());
        }
    }
}
