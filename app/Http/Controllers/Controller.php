<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function deploy()
    {
        $baseDir = '/home/vyrsite/jadwal.vyr23.site';
        chdir($baseDir);

        $output = [];

        // Stash changes
        $output[] = shell_exec('git stash 2>&1');

        // Pull changes
        $output[] = shell_exec('git pull 2>&1');

        // Update Composer dependencies
        $output[] = shell_exec('/opt/cpanel/composer/bin/composer update 2>&1');

        // Run artisan commands
        Artisan::call('migrate:fresh', ['--seed' => true]);
        $output[] = Artisan::output();

        echo "<pre>" . implode("\n", $output) . "</pre>";
    }
}
